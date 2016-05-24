<?php

/**
 * @file
 * Contains \Drupal\http_response_headers\EventSubscriber\AddHTTPHeaders.
 */

namespace Drupal\http_response_headers\EventSubscriber;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Provides AddHTTPHeaders.
 */
class AddHTTPHeaders implements EventSubscriberInterface {

  /**
   * Sets extra HTTP headers.
   */
  public function onRespond(FilterResponseEvent $event) {
    if (!$event->isMasterRequest()) {
      return;
    }
    $response = $event->getResponse();
    $config = \Drupal::config('http_response_headers.settings');

    // Security HTTP headers.
    $security = $config->get('security');
    foreach ($security as $param => $value) {
      if (!empty($value)) {
        $response->headers->set($param, $value);
      }
    }

  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[KernelEvents::RESPONSE][] = ['onRespond'];
    return $events;
  }

}
