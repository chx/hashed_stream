<?php

namespace Drupal\hashed_stream\StreamWrapper;

use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\StreamWrapper\PublicStream;

class HashedPublicStream extends PublicStream {

  use HashedStreamTrait;

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return t('Public hashed files');
  }

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    return t('Public local files served by the webserver stored in hashed dirs.');
  }

  public function getExternalUrl() {
    $path = str_replace('\\', '/', $this->getTarget());
    return static::baseUrl() . '/' . $this->getHashDir($this->uri) . '/' . UrlHelper::encodePath($path);
  }

}
