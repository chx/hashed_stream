<?php

namespace Drupal\hashed_stream\StreamWrapper;

trait HashedStreamTrait {

  /**
   * @return
   *   Something like ab/cd -- two levels of directories, 255 each level.
   */
  protected function getHashDir($uri) {
    $md5 = md5($uri);
    $hashDir = substr($md5, 0, 2) . '/' . substr($md5, 2, 2);
    $dir = static::basePath() . $hashDir;
    if (!is_dir($dir)) {
      drupal_mkdir($dir, NULL, TRUE);
    }
    return $hashDir;
  }

  protected function getLocalPath($uri = NULL) {
    if (!isset($uri)) {
      $uri = $this->uri;
    }

    $path = $this->getDirectoryPath() . '/' . $this->getHashDir($uri) . '/' . $this->getTarget($uri);

    // In PHPUnit tests, the base path for local streams may be a virtual
    // filesystem stream wrapper URI, in which case this local stream acts like
    // a proxy. realpath() is not supported by vfsStream, because a virtual
    // file system does not have a real filepath.
    if (strpos($path, 'vfs://') === 0) {
      return $path;
    }

    $realpath = realpath($path);

    if (!$realpath) {
      // This file does not yet exist.
      $dir = dirname($path);
      if (!is_dir($dir)) {
        drupal_mkdir($dir, NULL, TRUE);
      }
      $realpath = realpath($dir) . '/' . drupal_basename($path);
    }
    $directory = realpath(static::basePath());
    if (!$realpath || !$directory || strpos($realpath, $directory) !== 0) {
      return FALSE;
    }
    return $realpath;
  }

  public function mkdir($uri, $mode, $options) {
    $this->uri = $uri;
    $recursive = (bool) ($options & STREAM_MKDIR_RECURSIVE);
    // Unlike LocalStream(), our getLocalPath() deals with directories just
    // fine.
    $localpath = $this->getLocalPath($uri);
    if ($options & STREAM_REPORT_ERRORS) {
      return drupal_mkdir($localpath, $mode, $recursive);
    }
    else {
      return @drupal_mkdir($localpath, $mode, $recursive);
    }
  }

}
