<?php

namespace Drupal\hashed_stream;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceModifierInterface;
use Drupal\Core\DependencyInjection\ServiceProviderInterface;

class HashedStreamServiceProvider implements ServiceProviderInterface, ServiceModifierInterface {

  public function register(ContainerBuilder $container) {
  }

  public function alter(ContainerBuilder $container) {
    if ($container->hasDefinition('stream_wrapper.private')) {
      $container->getDefinition('stream_wrapper.private')->setClass(StreamWrapper\HashedPrivateStream::class);
    }
    $container->getDefinition('stream_wrapper.public')->setClass(StreamWrapper\HashedPublicStream::class);
  }
}
