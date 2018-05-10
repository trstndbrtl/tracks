<?php

namespace Drupal\tracks\Plugin\Importer;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\tracks\Entity\ImporterInterface;
use Drupal\tracks\Entity\TrackInterface;
use Drupal\tracks\Plugin\ImporterBase;

/**
 * Track importer from a JSON format.
 *
 * @Importer(
 *   id = "json",
 *   label = @Translation("JSON Importer")
 * )
 */
class JsonImporter extends ImporterBase {

  use StringTranslationTrait;

  /**
   * {@inheritdoc}
   */
  public function import() {
    $data = $this->getData();
    if (!$data) {
      return FALSE;
    }

    if (!isset($data->tracks)) {
      return FALSE;
    }

    $tracks = $data->tracks;
    $batch = [
      'title' => $this->t('Importing tracks'),
      'operations' => [
        [[$this, 'clearMissing'], [$tracks]],
        [[$this, 'importTracks'], [$tracks]],
      ],
      'finished' => [$this, 'importTracksFinished'],
    ];
    batch_set($batch);
    if (PHP_SAPI == 'cli') {
      drush_backend_batch_process();
    }

    return TRUE;
  }

  /**
   * Batch operation to remove the tracks which are no longer in the list of
   * tracks coming from the JSON file.
   *
   * @param $tracks
   * @param $context
   */
  public function clearMissing($tracks, &$context) {
    if (!isset($context['results']['cleared'])) {
      $context['results']['cleared'] = [];
    }

    if (!$tracks) {
      return;
    }

    $ids = [];
    foreach ($tracks as $track) {
      $ids[] = $track->id;
    }

    $ids = $this->entityTypeManager->getStorage('track')->getQuery()
      ->condition('remote_id', $ids, 'NOT IN')
      ->execute();
    if (!$ids) {
      $context['results']['cleared'] = [];
      return;
    }

    $entities = $this->entityTypeManager->getStorage('track')->loadMultiple($ids);

    /** @var TrackInterface $entity */
    foreach ($entities as $entity) {
      $context['results']['cleared'][] = $entity->getName();
    }
    $context['message'] = $this->t('Removing @count tracks', ['@count' => count($entities)]);
    $this->entityTypeManager->getStorage('track')->delete($entities);
  }

  /**
   * Batch operation to import the tracks from the JSON file.
   *
   * @param $tracks
   * @param $context
   */
  public function importTracks($tracks, &$context) {
    if (!isset($context['results']['imported'])) {
      $context['results']['imported'] = [];
    }

    if (!$tracks) {
      return;
    }

    $sandbox = &$context['sandbox'];
    if (!$sandbox) {
      $sandbox['progress'] = 0;
      $sandbox['max'] = count($tracks);
      $sandbox['tracks'] = $tracks;
    }

    $slice = array_splice($sandbox['tracks'], 0, 3);
    foreach ($slice as $track) {
      $context['message'] = $this->t('Importing track @name', ['@name' => $track->name]);
      $this->persistTrack($track);
      $context['results']['imported'][] = $track->name;
      $sandbox['progress']++;
    }

    $context['finished'] = $sandbox['progress'] / $sandbox['max'];
  }

  /**
   * Callback for when the batch processing completes.
   *
   * @param $success
   * @param $results
   * @param $operations
   */
  public function importTracksFinished($success, $results, $operations) {
    if (!$success) {
      drupal_set_message($this->t('There was a problem with the batch'), 'error');
      return;
    }

    $cleared = count($results['cleared']);
    if ($cleared == 0) {
      drupal_set_message($this->t('No tracks had to be deleted.'));
    }
    else {
      drupal_set_message($this->formatPlural($cleared, '1 track had to be deleted.', '@count tracks had to be deleted.'));
    }

    $imported = count($results['imported']);
    if ($imported == 0) {
      drupal_set_message($this->t('No tracks found to be imported.'));
    }
    else {
      drupal_set_message($this->formatPlural($imported, '1 track imported.', '@count tracks imported.'));
    }
  }

  /**
   * Loads the track data from the remote URL.
   *
   * @return \stdClass
   */
  private function getData() {
    /** @var ImporterInterface $importer_config */
    $importer_config = $this->configuration['config'];
    $config = $importer_config->getPluginConfiguration();
    $url = isset($config['url']) ? $config['url'] : NULL;
    if (!$url) {
      return NULL;
    }
    $request = $this->httpClient->get($url);
    $string = $request->getBody();
    return json_decode($string);
  }

  /**
   * Saves a Track entity from the remote data.
   *
   * @param \stdClass $data
   */
  private function persistTrack($data) {
    /** @var ImporterInterface $config */
    $config = $this->configuration['config'];

    $existing = $this->entityTypeManager->getStorage('track')->loadByProperties(['remote_id' => $data->id, 'source' => $config->getSource()]);
    if (!$existing) {
      $values = [
        'remote_id' => $data->id,
        'source' => $config->getSource(),
        'type' => $config->getBundle(),
      ];
      /** @var TrackInterface $track */
      $track = $this->entityTypeManager->getStorage('track')->create($values);
      $track->setName($data->name);
      $track->setTrackNumber($data->number);
      $this->handleTrackImage($data, $track);
      $track->save();
      return;
    }

    if (!$config->updateExisting()) {
      return;
    }

    /** @var TrackInterface $track */
    $track = reset($existing);
    $track->setName($data->name);
    $track->setTrackNumber($data->number);
    $this->handleTrackImage($data, $track);
    $track->save();
  }

  /**
   * Imports the image of the track and adds it to the Track entity.
   *
   * @param $data
   * @param TrackInterface $track
   */
  private function handleTrackImage($data, TrackInterface $track) {
    $name = $data->image;
    $image = file_get_contents('tracks://' . $name);
    if (!$image) {
      // Perhaps log something.
      return;
    }

    /** @var \Drupal\file\FileInterface $file */
    $file = file_save_data($image, 'public://track_images/' . $name, FILE_EXISTS_REPLACE);
    if (!$file) {
      // Something went wrong, perhaps log it.
      return;
    }

    $track->setImage($file->id());
  }

  /**
   * {@inheritdoc}
   */
  public function getConfigurationForm(\Drupal\tracks\Entity\ImporterInterface $importer) {
    $form = [];
    $config = $importer->getPluginConfiguration();
    $form['url'] = [
      '#type' => 'url',
      '#default_value' => isset($config['url']) ? $config['url'] : '',
      '#title' => $this->t('Url'),
      '#description' => $this->t('The URL to the import resource'),
      '#required' => TRUE,
    ];
    return $form;
  }
}