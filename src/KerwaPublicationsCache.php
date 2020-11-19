<?php

namespace Drupal\kerwa_publications;

/**
 * Kerwa Publications cache refresher.
 */

class KerwaPublicationsCache {

  // @TODO: Document params.
  // @TODO: DI.

  /**
   * Get from cache or empty.
   */
  public function getCachedData($option) {
    $cache = \Drupal::service('cache.default');
    $cache_id = 'kerwa_publications_' . $option->id();
    $data = $cache->get($cache_id);
    if (!empty($data)) {
      $data = $data->data;
    }
    return $data;
  }

  /**
   * Refresh cache.
   */
  public function refreshCache($option) {
    $cache = \Drupal::service('cache.default');
    $client = \Drupal::httpClient();
    $cache_id = 'kerwa_publications_' . $option->id();
    $data = [
      'key' => $option->getKey(),
      'value' => $option->getValue(),
      'language' => $option->getLanguage(),
    ];
    $request = $client->post('https://kerwa.ucr.ac.cr:8443/rest/items/find-by-metadata-field', [
      'json' => $data,
      'verify' => FALSE,
    ]);
    $items = json_decode($request->getBody());
    $publications = [];
    foreach ($items as $item) {
      $publication = [];
      $url = 'https://kerwa.ucr.ac.cr:8443' . $item->link . '/metadata';
      $item_request = $client->get($url, [
        'verify' => FALSE,
      ]);
      $publication_item = json_decode($item_request->getBody());
      foreach ($publication_item as $item_value) {
        if ($item_value->key === 'dc.title') {
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
        if ($item_value->key === 'dc.identifier.uri ') {
          $publication['uri'] = $item_value->value;
        }
        $publications[] = $publication;
      }
    }
    $cache->set($cache_id, $publications, \Drupal::time()->getCurrentTime() + 86400);
  }
}
