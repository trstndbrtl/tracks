<?php

namespace Drupal\tracks\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;
use Drupal\Core\Url;

/**
 * Importer configuration entity.
 */
interface ImporterInterface extends ConfigEntityInterface {

  /**
   * Returns the Importer plugin ID to be used by this importer.
   *
   * @return string
   */
  public function getPluginId();

  /**
   * Returns the configuration specific to the chosen plugin.
   *
   * @return array
   */
  public function getPluginConfiguration();

  /**
   * Whether or not to update existing tracks if they have already been imported.
   *
   * @return bool
   */
  public function updateExisting();

  /**
   * Returns the source of the tracks.
   *
   * @return string
   */
  public function getSource();

  /**
   * Returns the Track type that needs to be created.
   *
   * @return string
   */
  public function getBundle();
}
