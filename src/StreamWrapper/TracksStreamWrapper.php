<?php

namespace Drupal\tracks\StreamWrapper;

use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\StreamWrapper\StreamWrapperInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Stream wrapper for the remote track image paths used by the JSON Importer.
 */
class TracksStreamWrapper implements StreamWrapperInterface {

  use StringTranslationTrait;

  /**
   * The Stream URI
   *
   * @var string
   */
  protected $uri;

  /**
   * @var \Drupal\Core\Site\Settings
   */
  protected $settings;

  /**
   * Resource handle
   *
   * @var resource
   */
  protected $handle;

  /**
   * TracksStreamWrapper constructor.
   */
  public function __construct() {
    // Dependency injection does not work with stream wrappers.
    $this->settings = \Drupal::service('settings');
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->t('Track images stream wrapper');
  }

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    return $this->t('Stream wrapper for the remote location where track images can be found by the JSON Importer.');
  }

  /**
   * {@inheritdoc}
   */
  public static function getType() {
    return StreamWrapperInterface::HIDDEN;
  }

  /**
   * {@inheritdoc}
   */
  public function setUri($uri) {
    $this->uri = $uri;
  }

  /**
   * {@inheritdoc}
   */
  public function getUri() {
    return $this->uri;
  }

  /**
   * Helper method that returns the local writable target of the resource within the stream.
   *
   * @param null $uri
   *
   * @return string
   */
  public function getTarget($uri = NULL) {
    if (!isset($uri)) {
      $uri = $this->uri;
    }

    list($scheme, $target) = explode('://', $uri, 2);
    return trim($target, '\/');
  }

  /**
   * {@inheritdoc}
   */
  public function getExternalUrl() {
    $path = str_replace('\\', '/', $this->getTarget());
    return $this->settings->get('track_images_path') . '/' . UrlHelper::encodePath($path);
  }

  /**
   * {@inheritdoc}
   */
  public function realpath() {
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function stream_open($path, $mode, $options, &$opened_path) {
    $allowed_modes = array('r', 'rb');
    if (!in_array($mode, $allowed_modes)) {
      return FALSE;
    }
    $this->uri = $path;
    $url = $this->getExternalUrl();
    $this->handle = ($options && STREAM_REPORT_ERRORS) ? fopen($url, $mode) : @fopen($url, $mode);
    return (bool) $this->handle;
  }

  /**
   * {@inheritdoc}
   */
  public function dir_closedir() {
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function dir_opendir($path, $options) {
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function dir_readdir() {
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function dir_rewinddir() {
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function mkdir($path, $mode, $options) {
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function rename($path_from, $path_to) {
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function rmdir($path, $options) {
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function stream_cast($cast_as) {
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function stream_close() {
    return fclose($this->handle);
  }

  /**
   * {@inheritdoc}
   */
  public function stream_eof() {
    return feof($this->handle);
  }

  /**
   * {@inheritdoc}
   */
  public function stream_flush() {
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function stream_lock($operation) {
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function stream_metadata($path, $option, $value) {
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function stream_read($count) {
    return fread($this->handle, $count);
  }

  /**
   * {@inheritdoc}
   */
  public function stream_seek($offset, $whence = SEEK_SET) {
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function stream_set_option($option, $arg1, $arg2) {
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function stream_stat() {
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function stream_tell() {
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function stream_truncate($new_size) {
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function stream_write($data) {
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function unlink($path) {
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function url_stat($path, $flags) {
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function dirname($uri = NULL) {
    return FALSE;
  }
}