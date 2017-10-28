<?php

namespace Drupal\hashed_stream\StreamWrapper;

use Drupal\Core\StreamWrapper\PrivateStream;

class HashedPrivateStream extends PrivateStream {

  use HashedStreamTrait;

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return t('Private hashed files');
  }

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    return t('Private local files served by Drupal, stored in hashed directories.');
  }


}
