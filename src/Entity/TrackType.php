<?php

namespace Drupal\tracks\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Track type configuration entity type.
 *
 * @ConfigEntityType(
 *   id = "track_type",
 *   label = @Translation("Track type"),
 *   handlers = {
 *     "list_builder" = "Drupal\tracks\TrackTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\tracks\Form\TrackTypeForm",
 *       "edit" = "Drupal\tracks\Form\TrackTypeForm",
 *       "delete" = "Drupal\tracks\Form\TrackTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "track_type",
 *   admin_permission = "administer site configuration",
 *   bundle_of = "track",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/track_type/{track_type}",
 *     "add-form" = "/admin/structure/track_type/add",
 *     "edit-form" = "/admin/structure/track_type/{track_type}/edit",
 *     "delete-form" = "/admin/structure/track_type/{track_type}/delete",
 *     "collection" = "/admin/structure/track_type"
 *   }
 * )
 */
class TrackType extends ConfigEntityBundleBase implements TrackTypeInterface  {

  /**
   * The Track type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Track type label.
   *
   * @var string
   */
  protected $label;

}
