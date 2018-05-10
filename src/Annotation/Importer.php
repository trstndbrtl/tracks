<?php

namespace Drupal\tracks\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines an Importer item annotation object.
 *
 * @see \Drupal\tracks\Plugin\ImporterManager
 *
 * @Annotation
 */
class Importer extends Plugin {

  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The label of the plugin.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $label;

}
