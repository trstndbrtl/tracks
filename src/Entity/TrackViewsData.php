<?php

namespace Drupal\tracks\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Track entities.
 */
class TrackViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['track']['importer'] = [
      'title' => t('Importer'),
      'help' => t('Information about the Track importer.'),
      'field' => array(
        'id' => 'track_importer',
      ),
    ];

    return $data;
  }
}