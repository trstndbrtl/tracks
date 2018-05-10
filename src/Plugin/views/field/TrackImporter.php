<?php

namespace Drupal\tracks\Plugin\views\field;

use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Form\FormStateInterface;
use Drupal\tracks\Plugin\ImporterManager;
use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\views\ResultRow;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Field plugin that renders data about the Importer that imported the Track.
 *
 * @ViewsField("track_importer")
 */
class TrackImporter extends FieldPluginBase {

  /**
   * @var \Drupal\Core\Entity\EntityTypeManager
   */
  protected $entityTypeManager;

  /**
   * @var \Drupal\tracks\Plugin\ImporterManager
   */
  protected $importerManager;

  /**
   * Constructs a TrackImporter object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Entity\EntityTypeManager $entityTypeManager
   * @param \Drupal\tracks\Plugin\ImporterManager $importerManager
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManager $entityTypeManager, ImporterManager $importerManager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entityTypeManager;
    $this->importerManager = $importerManager;
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
      $container->get('tracks.importer_manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function query() {
    // Leave empty to avoid a query on this field.
  }

  /**
   * {@inheritdoc}
   */
  protected function defineOptions() {
    $options = parent::defineOptions();
    $options['importer'] = array('default' => 'entity');

    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {

    $form['importer'] = array(
      '#type' => 'select',
      '#title' => $this->t('Importer'),
      '#description' => $this->t('Which importer label to use?'),
      '#options' => [
        'entity' => $this->t('Entity'),
        'plugin' => $this->t('Plugin')
      ],
      '#default_value' => $this->options['importer'],
    );

    parent::buildOptionsForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function render(ResultRow $values) {
    /** @var \Drupal\tracks\Entity\TrackInterface $track */
    $track = $values->_entity;
    $source = $track->getSource();
    $importers = $this->entityTypeManager->getStorage('importer')->loadByProperties(['source' => $source]);
    if (!$importers) {
      return NULL;
    }

    // We'll assume one importer per source.
    /** @var \Drupal\tracks\Entity\ImporterInterface $importer */
    $importer = reset($importers);

    // If we want to show the entity label.
    if ($this->options['importer'] == 'entity') {
      return $this->sanitizeValue($importer->label());
    }

    // Otherwise we show the plugin label.
    $definition = $this->importerManager->getDefinition($importer->getPluginId());
    return $this->sanitizeValue($definition['label']);
  }
}
