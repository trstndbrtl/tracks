<?php

namespace Drupal\tracks\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\Core\Url;

/**
 * Defines the Importer entity.
 *
 * @ConfigEntityType(
 *   id = "importer",
 *   label = @Translation("Importer"),
 *   handlers = {
 *     "list_builder" = "Drupal\tracks\ImporterListBuilder",
 *     "form" = {
 *       "add" = "Drupal\tracks\Form\ImporterForm",
 *       "edit" = "Drupal\tracks\Form\ImporterForm",
 *       "delete" = "Drupal\tracks\Form\ImporterDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "importer",
 *   admin_permission = "administer site configuration",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "add-form" = "/admin/structure/importer/add",
 *     "edit-form" = "/admin/structure/importer/{importer}/edit",
 *     "delete-form" = "/admin/structure/importer/{importer}/delete",
 *     "collection" = "/admin/structure/importer"
 *   }
 * )
 */
class Importer extends ConfigEntityBase implements ImporterInterface {

  /**
   * The Importer ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Importer label.
   *
   * @var string
   */
  protected $label;

  /**
   * The plugin ID of the plugin to be used for processing this import.
   *
   * @var string
   */
  protected $plugin;

  /**
   * The configuration specific to the plugin.
   *
   * @var array
   */
  protected $plugin_configuration;

  /**
   * Whether or not to update existing tracks if they have already been imported.
   *
   * @var bool
   */
  protected $update_existing = TRUE;

  /**
   * The source of the tracks.
   *
   * @var string
   */
  protected $source;

  /**
   * The track bundle.
   *
   * @var string
   */
  protected $bundle;

  /**
   * {@inheritdoc}
   */
  public function getPluginId() {
    return $this->plugin;
  }

  /**
   * {@inheritdoc}
   */
  public function getPluginConfiguration() {
    return $this->plugin_configuration;
  }

  /**
   * {@inheritdoc}
   */
  public function updateExisting() {
    return $this->update_existing;
  }

  /**
   * {@inheritdoc}
   */
  public function getSource() {
    return $this->source;
  }

  /**
   * {@inheritdoc}
   */
  public function getBundle() {
    return $this->bundle;
  }
}
