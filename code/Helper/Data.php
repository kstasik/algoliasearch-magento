<?php

require 'algoliasearch.php';

class Algolia_Algoliasearch_Helper_Data extends Mage_Core_Helper_Abstract {

  public function getIndex($name) {
    return $this->getClient()->initIndex($name);
  }

  public function listIndexes() {
    return $this->getClient()->listIndexes();
  }

  public function query($index, $q, $params) {
    return $this->getClient()->initIndex($index)->search($q, $params);
  }

  public function getProductJSON($product) {
    $categories = array();
    foreach ($product->getCategoryIds() as $catId) {
      array_push($categories, Mage::getModel('catalog/category')->setStoreId($product->getStoreId())->load($catId)->getName());
    }
    return array(
      'objectID' => $product->getStoreId() . '_' . $product->getId(),
      'name' => $product->getName(),
      'categories' => $categories,
      'description' => $product->getDescription(),
      'price' => $product->getPrice(),
      'url' => $product->getUrlInStore(),
      '_tags' => array("store_" . $product->getStoreId())
      //'thumbnail_url' => $product->getThumbnailUrl(),
      //'image_url' => $product->getImageUrl()
    );
  }

  public function getCategoryJSON($cat) {
    $path = '';
    foreach ($cat->getPathIds() as $catId) {
      if ($path != '') {
        $path .= ' / ';
      }
      $path .= Mage::getModel('catalog/category')->setStoreId($cat->getStoreId())->load($catId)->getName();
    }
    return array(
      'objectID' => $cat->getStoreId() . '_' . $cat->getId(),
      'name' => $cat->getName(),
      'path' => $path,
      'level' => $cat->getLevel(),
      'url' => $cat->getUrl(),
      'product_count' => $cat->getProductCount(),
      '_tags' => array("store_" . $cat->getStoreId())
      //'image_url' => $cat->getImageUrl()
    );
  }

  private function getClient() {
    return new \AlgoliaSearch\Client($this->getApplicationID(), $this->getAPIKey());
  }

  public function getApplicationID() {
    return Mage::getStoreConfig('algoliasearch/settings/application_id');
  }

  public function getAPIKey() {
    return Mage::getStoreConfig('algoliasearch/settings/api_key');
  }

  public function getSearchOnlyAPIKey() {
    return Mage::getStoreConfig('algoliasearch/settings/search_only_api_key');
  }

}