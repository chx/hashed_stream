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

  /**
   * @inheritDoc
   */
  protected function drupalGetTestFiles($type, $size = NULL) {
    // Scanning public:// is by and large broken so we override this method to
    // file_scan_directory() the path instead.
    $basePath = PublicStream::basePath();
    if (empty($this->generatedTestFiles)) {
      // Generate binary test files.
      $lines = [64, 1024];
      $count = 0;
      foreach ($lines as $line) {
        $this->generateFile('binary-' . $count++, 64, $line, 'binary');
      }

      // Generate ASCII text test files.
      $lines = [16, 256, 1024, 2048, 20480];
      $count = 0;
      foreach ($lines as $line) {
        $this->generateFile('text-' . $count++, 64, $line, 'text');
      }

      // Copy other test files from simpletest.
      $original = drupal_get_path('module', 'simpletest') . '/files';
      $files = file_scan_directory($original, '/(html|image|javascript|php|sql)-.*/');
      foreach ($files as $file) {
        file_unmanaged_copy($file->uri, $basePath);
      }

      $this->generatedTestFiles = TRUE;
    }

    $files = [];
    // Make sure type is valid.
    if (in_array($type, ['binary', 'html', 'image', 'javascript', 'php', 'sql', 'text'])) {
      $files = file_scan_directory($basePath, '/' . $type . '\-.*/');

      // If size is set then remove any files that are not of that size.
      if ($size !== NULL) {
        foreach ($files as $file) {
          $stats = stat($file->uri);
          if ($stats['size'] != $size) {
            unset($files[$file->uri]);
          }
        }
      }
    }
    usort($files, [$this, 'compareFiles']);
    return $files;
  }


}

