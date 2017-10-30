<?php

namespace Drupal\hashed_stream\Tests;

use Drupal\Core\StreamWrapper\PublicStream;
use Drupal\file\Tests\FilePrivateTest;

/**
 * Uploads a test to a private node and checks access.
 *
 * @group file
 */
class HashedFilePrivateTest extends FilePrivateTest {

  public static $modules = ['hashed_stream'];

}

