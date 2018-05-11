<?php

namespace Drupal\tracks\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;

/**
 * Represents a Track entity.
 */
interface TrackInterface extends ContentEntityInterface, EntityChangedInterface {

  /**
   * Gets the Track name.
   *
   * @return string
   */
  public function getName();

  /**
   * Sets the Track name.
   *
   * @param string $name
   *
   * @return \Drupal\tracks\Entity\TrackInterface
   *   The called Track entity.
   */
  public function setName($name);

  /**
   * Gets the Track number.
   *
   * @return int
   */
  public function getTrackNumber();

  /**
   * Sets the Track number.
   *
   * @param int $number
   *
   * @return \Drupal\tracks\Entity\TrackInterface
   *   The called Track entity.
   */
  public function setTrackNumber($number);

  /**
   * Gets the Track remote ID.
   *
   * @return string
   */
  public function getRemoteId();

  /**
   * Sets the Track remote ID.
   *
   * @param string $id
   *
   * @return \Drupal\tracks\Entity\TrackInterface
   *   The called Track entity.
   */
  public function setRemoteId($id);

  /**
   * Gets the Track source.
   *
   * @return string
   */
  public function getSource();

  /**
   * Sets the Track source.
   *
   * @param string $source
   *
   * @return \Drupal\tracks\Entity\TrackInterface
   *   The called Track entity.
   */
  public function setSource($source);

  /**
   * Gets the Track platform.
   *
   * @return string
   */
  public function getPlatform();

  /**
   * Sets the Track platform.
   *
   * @param string $platform
   *
   * @return \Drupal\tracks\Entity\TrackInterface
   *   The called Track entity.
   */
  public function setPlatform($platform);

/**
   * Gets the Track platform display.
   *
   * @return string
   */
  public function getPlatformDisplay();

  /**
   * Sets the Track platform display.
   *
   * @param string $platformDisplay
   *
   * @return \Drupal\tracks\Entity\TrackInterface
   *   The called Track entity.
   */
  public function setPlatformDisplay($platformDisplay);

  /**
   * Gets the Track url.
   *
   * @return string
   */
  public function getTrackUrl();

  /**
   * Sets the Track url.
   *
   * @param string $url
   *
   * @return \Drupal\tracks\Entity\TrackInterface
   *   The called Track entity.
   */
  public function setTrackUrl($trackUrl);

  /**
   * Gets the Track image.
   *
   * @return \Drupal\file\FileInterface
   */
  public function getImage();

  /**
   * Sets the Track image.
   *
   * @param int $image
   *
   * @return \Drupal\tracks\Entity\TrackInterface
   *   The called Track entity.
   */
  public function setImage($image);

  /**
   * Gets the Track creation timestamp.
   *
   * @return int
   */
  public function getCreatedTime();

  /**
   * Sets the Track creation timestamp.
   *
   * @param int $timestamp
   *
   * @return \Drupal\tracks\Entity\TrackInterface
   *   The called Track entity.
   */
  public function setCreatedTime($timestamp);

}