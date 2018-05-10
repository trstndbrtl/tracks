<?php

namespace Drupal\tracks\Plugin\Importer;

use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\StreamWrapper\StreamWrapperManagerInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\tracks\Entity\ImporterInterface;
use Drupal\tracks\Entity\TrackInterface;
use Drupal\tracks\Plugin\ImporterBase;
use GuzzleHttp\Client;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Track importer from a JSON format.
 *
 * @Importer(
 *   id = "csv",
 *   label = @Translation("CSV Importer")
 * )
 */
class CsvImporter extends ImporterBase {

  use StringTranslationTrait;

  /**
   * @var \Drupal\Core\StreamWrapper\StreamWrapperManagerInterface
   */
  protected $streamWrapperManager;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManager $entityTypeManager, Client $httpClient, StreamWrapperManagerInterface $streamWrapperManager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $entityTypeManager, $httpClient);
    $this->streamWrapperManager = $streamWrapperManager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
      $container->get('http_client'),
      $container->get('stream_wrapper_manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function import() {
    $tracks = $this->getData();
    if (!$tracks) {
      return FALSE;
    }

    foreach ($tracks as $track) {
      $this->persistTrack($track);
    }

    return TRUE;
  }

  /**
   * Loads the track data from the remote URL.
   *
   * @return array
   */
  private function getData() {
    /** @var ImporterInterface $importer_config */
    $importer_config = $this->configuration['config'];
    $config = $importer_config->getPluginConfiguration();
    $fids = isset($config['file']) ? $config['file'] : [];
    if (!$fids) {
      return NULL;
    }

    $fid = reset($fids);
    /** @var \Drupal\file\FileInterface $file */
    $file = $this->entityTypeManager->getStorage('file')->load($fid);
    $wrapper = $this->streamWrapperManager->getViaUri($file->getFileUri());
    if (!$wrapper) {
      return NULL;
    }

    // This is how we get the external URL based on a URI using the wrapper.
    $url = $wrapper->getExternalUrl();
    // But we can also create an /SplFileObject straight with the URI and it's actually
    // better so it works also in our testing environment which emulates the file
    // system.
    $spl = new \SplFileObject($file->getFileUri(), 'r');
    $data = [];
    while (!$spl->eof()) {
      $data[] = $spl->fgetcsv();
    }

    $tracks = [];
    $header = [];
    foreach ($data as $key => $row) {
      if ($key == 0) {
        $header = $row;
        continue;
      }

      if ($row[0] == "") {
        continue;
      }

      $track = new \stdClass();
      foreach ($header as $header_key => $label) {
        $track->{$label} = $row[$header_key];
      }
      $tracks[] = $track;
    }

    return $tracks;
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
    $track->save();
  }

  /**
   * {@inheritdoc}
   */
  public function getConfigurationForm(\Drupal\tracks\Entity\ImporterInterface $importer) {
    $form = [];
    $config = $importer->getPluginConfiguration();
    $form['file'] = [
      '#type' => 'managed_file',
      '#default_value' => isset($config['file']) ? $config['file'] : '',
      '#title' => $this->t('File'),
      '#description' => $this->t('The CSV file containing the track records.'),
      '#required' => TRUE,
      '#upload_validators' => [
        'file_validate_extensions' => ['csv'],
      ],
      '#upload_location' => 'public://'
    ];

    return $form;
  }

}