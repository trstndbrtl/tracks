<?php

namespace Drupal\tracks\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;


/**
 * Defines the Track entity.
 *
 * @ContentEntityType(
 *   id = "track",
 *   label = @Translation("Track"),
 *   bundle_label = @Translation("Track type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\tracks\TrackListBuilder",
 *     "views_data" = "Drupal\tracks\Entity\TrackViewsData",
 *     "form" = {
 *       "default" = "Drupal\tracks\Form\TrackForm",
 *       "add" = "Drupal\tracks\Form\TrackForm",
 *       "edit" = "Drupal\tracks\Form\TrackForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm",
 *     },
 *    "route_provider" = {
 *      "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider"
 *    },
 *   "access" = "Drupal\tracks\Access\TrackAccessControlHandler",
 *   },
 *   base_table = "track",
 *   admin_permission = "administer site configuration",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "bundle" = "type",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/track/{track}",
 *     "add-page" = "/admin/structure/track/add",
 *     "add-form" = "/admin/structure/track/add/{track_type}",
 *     "edit-form" = "/admin/structure/track/{track}/edit",
 *     "delete-form" = "/admin/structure/track/{track}/delete",
 *     "collection" = "/admin/structure/track",
 *   },
 *   bundle_entity_type = "track_type",
 *   field_ui_base_route = "entity.track_type.edit_form"
 * )
 */
class Track extends ContentEntityBase implements TrackInterface {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('name', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getTrackNumber() {
    return $this->get('number')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setTrackNumber($number) {
    $this->set('number', $number);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getRemoteId() {
    return $this->get('remote_id')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setRemoteId($id) {
    $this->set('remote_id', $id);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getSource() {
    return $this->get('source')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setSource($source) {
    $this->set('source', $source);
    return $this;
  }

    /**
   * {@inheritdoc}
   */
  public function getPlatform() {
    return $this->get('platform')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setPlatform($platform) {
    $this->set('platform', $platform);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getPlatformDisplay() {
    return $this->get('platform_display')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setPlatformDisplay($platformDisplay) {
    $this->set('platform_display', $platformDisplay);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getTrackUrl() {
    return $this->get('track_url')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setTrackUrl($trackUrl) {
    $this->set('track_url', $trackUrl);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getImage() {
    return $this->get('image')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function setImage($image) {
    $this->set('image', $image);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of the Track.'))
      ->setSettings([
        'max_length' => 255,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['number'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Number'))
      ->setDescription(t('The Track number.'))
      ->setSettings([
        'min' => 1,
        'max' => 10000
      ])
      ->setDefaultValue(NULL)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'number_unformatted',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'number',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['remote_id'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Remote ID'))
      ->setDescription(t('The remote ID of the Track.'))
      ->setSettings([
        'max_length' => 255,
        'text_processing' => 0,
      ])
      ->setDefaultValue('');

    $fields['source'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Source'))
      ->setDescription(t('The source of the Track.'))
      ->setSettings([
        'max_length' => 255,
        'text_processing' => 0,
      ])
      ->setDefaultValue('');

    $fields['platform'] = BaseFieldDefinition::create('list_string')
      ->setLabel(t('Platform'))
      ->setRequired(true)
      ->setSettings(array(
        'allowed_values' => [
          'soundcloud' => t('Soundcloud'),
          'mixcloud' => t('Mixcloud'),
          'youtube' => t('Youtube'),
          'vimeo' => t('Vimeo'),
        ],
      ))
      ->setDefaultValue('soundcloud')
      ->setDisplayOptions('form', array(
        'type' => 'options_select',
        'weight' => -3,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
    
    $fields['platform_display'] = BaseFieldDefinition::create('list_string')
      ->setLabel(t('Platform display'))
      ->setRequired(true)
      ->setSettings(array(
        'allowed_values' => [
          'sonore' => t('Sonore'),
          'visuel' => t('Visual'),
          'text' => t('Text'),
        ],
      ))
      ->setDefaultValue('sonore')
      ->setDisplayOptions('form', array(
        'type' => 'options_select',
        'weight' => -3,
      ))
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['track_url'] = BaseFieldDefinition::create('string')
      ->setLabel(t('track url'))
      ->setDescription(t('The url of the Track.'))
      ->setSettings([
        'max_length' => 955,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['image'] = BaseFieldDefinition::create('image')
      ->setLabel(t('Image'))
      ->setDescription(t('The track image.'))
      ->setDisplayOptions('form', array(
        'type' => 'image_image',
        'weight' => 5,
      ))
      ->setDisplayOptions('view', array(
        'type' => 'image',
        'weight' => 10,
        'settings' => [
          'image_style' => 'large'
        ]
      ));

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    return $fields;
  }
}
