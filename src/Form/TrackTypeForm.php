<?php

namespace Drupal\tracks\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\tracks\Entity\TrackTypeInterface;

/**
 * Form handler for creating/editing TrackType entities
 */
class TrackTypeForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    /** @var TrackTypeInterface $track_type */
    $track_type = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $track_type->label(),
      '#description' => $this->t('Label for the Track type.'),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $track_type->id(),
      '#machine_name' => [
        'exists' => '\Drupal\tracks\Entity\TrackType::load',
      ],
      '#disabled' => !$track_type->isNew(),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $track_type = $this->entity;
    $status = $track_type->save();

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Track type.', [
          '%label' => $track_type->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Track type.', [
          '%label' => $track_type->label(),
        ]));
    }
    $form_state->setRedirectUrl($track_type->toUrl('collection'));
  }

}
