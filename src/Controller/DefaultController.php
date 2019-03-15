<?php

namespace Drupal\islandora_book\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Access\AccessResult;

/**
 * Default controller for the islandora_book module.
 */
class DefaultController extends ControllerBase {

  /**
   * Add zipped pages callback.
   */
  public function addZippedPages($object = NULL) {
    module_load_include('inc', 'islandora_paged_content', 'includes/manage_pages');
    return islandora_paged_content_ingest_zipped_pages(
      $object,
      ['tif', 'tiff', 'jpg', 'jpeg', 'jp2'],
      'islandora:pageCModel',
      $this->config('islandora_book.settings')->get('islandora_book_ingest_derivatives')
    );
  }

  /**
   * Add page callback.
   */
  public function addPage($object = NULL) {
    module_load_include('inc', 'islandora_book', 'includes/manage_book');
    return islandora_book_ingest_page($object);
  }

  /**
   * Pages display generator.
   */
  public function pages($object = NULL) {
    return islandora_book_pages_menu($object);
  }

  /**
   * Access callback function for display of pages.
   */
  public function pagesAccess($object = NULL) {
    $object = islandora_object_load($object);
    $permissions = [ISLANDORA_VIEW_OBJECTS];
    $content_models = ['islandora:bookCModel'];
    $perm = islandora_user_access_check($object, $permissions, $content_models);
    return AccessResult::allowedIf($perm)
      ->addCacheableDependency($object)
      ->cachePerPermissions();
  }

}
