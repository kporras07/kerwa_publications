<?php

namespace Drupal\kerwa_publications;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\kerwa_publications\Entity\KerwaOption;
use GuzzleHttp\Client;

/**
 * Kerwa Publications cache refresher.
 */

class KerwaPublicationsCache {

  /**
   * Cache service.
   *
   * @var \Drupal\Core\Cache\CacheBackendInterface
   */
  protected $cache;

  /**
   * Http client.
   *
   * @var \GuzzleHttp\Client
   */
  protected $httpClient;

  /**
   * Construct.
   *
   * @param \Drupal\Core\Cache\CacheBackendInterface
   *   The cache service.
   * @param \GuzzleHttp\Client
   *   The http client.
   */
  public function __construct(CacheBackendInterface $cache, Client $http_client) {
    $this->cache = $cache;
    $this->httpClient = $http_client;
  }

  /**
   * Get from cache or empty.
   *
   * @param \Drupal\kerwa_publications\Entity\KerwaOption $option
   *   Kerwa option entity.
   */
  public function getCachedData(KerwaOption $option) {
    $cache_id = 'kerwa_publications_' . $option->id();
    $data = $this->cache->get($cache_id);
    if (!empty($data)) {
      $data = $data->data;
    }
    return $data;
  }

  /**
   * Refresh cache.
   *
   * @param \Drupal\kerwa_publications\Entity\KerwaOption $option
   *   Kerwa option entity.
   */
  public function refreshCache(KerwaOption $option) {
    $cache_id = 'kerwa_publications_' . $option->id();
    $data = [
      'key' => $option->getKey(),
      'value' => $option->getValue(),
      'language' => $option->getLanguage(),
    ];
    $request = $this->httpClient->post('https://kerwa.ucr.ac.cr:8443/rest/items/find-by-metadata-field', [
      'json' => $data,
      'verify' => FALSE,
    ]);
    $items = json_decode($request->getBody());
    $publications = [];
    foreach ($items as $item) {
      $publication = [];
      $url = 'https://kerwa.ucr.ac.cr:8443' . $item->link . '/metadata';
      $item_request = $this->httpClient->get($url, [
        'verify' => FALSE,
      ]);
      $publication_item = json_decode($item_request->getBody());
      foreach ($publication_item as $item_value) {
        if ($item_value->key === 'dc.title') {
          if (!empty($publication['title']) && $item_value->language === 'en') {
            continue;
          }
          $publication['title'] = $item_value->value;
        }
        if ($item_value->key === 'dc.creator') {
          if (!isset($publication['creator'])) {
            $publication['creator'] = [];
          }
          $publication['creator'][] = $item_value->value;
        }
        if ($item_value->key === 'dc.type') {
          if (!isset($publication['type'])) {
            $publication['type'] = [];
          }
          $publication['type'][] = $item_value->value;
        }
        if ($item_value->key === 'dc.date.issued') {
          $publication['date'] = $item_value->value;
        }
        if ($item_value->key === 'dc.identifier.uri') {
          $publication['uri'] = $item_value->value;
        }
      }
      $publications[] = $publication;
    }
    $this->cache->set($cache_id, $publications, CacheBackendInterface::CACHE_PERMANENT);
  }
}
