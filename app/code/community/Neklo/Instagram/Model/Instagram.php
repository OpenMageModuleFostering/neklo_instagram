<?php
/*
NOTICE OF LICENSE

This source file is subject to the NekloEULA that is bundled with this package in the file ICENSE.txt.

It is also available through the world-wide-web at this URL: http://store.neklo.com/LICENSE.txt

Copyright (c)  Neklo (http://store.neklo.com/)
*/

class Neklo_Instagram_Model_Instagram
{
    protected $_api = null;

    /**
     * Returns Instagram API model.
     *
     * @return Neklo_Instagram_Model_Instagram_Api
     */
    protected function getAPI() {
        if ($this->_api) {
            return $this->_api;
        }

        $this->_api = Mage::getModel('neklo_instagram/instagram_api', $this->getHelper()->getClientId());
        return $this->_api;
    }

    public function getTagMedia($name, $limit = 0) {
        try {
            $response = $this->getAPI()->getTagMedia($name, $limit);
        } catch (Exception $e) {
            Mage::logException($e);
			return array();
        }
        $collection = new Varien_Data_Collection();
        if (!isset($response->data) || !is_array($response->data)) {
            return $collection;
        }

        foreach ($response->data as $item) {
            if (!isset($item->images->low_resolution->url)) {
                continue;
            }

            $image = new Varien_Object();
            $image->setUrl($item->images->low_resolution->url);

            if (isset($item->caption->text)) {
                $image->setName($item->caption->text);
            }

            if (isset($item->link)) {
                $image->setLink($item->link);
            }

            $collection->addItem($image);
        }

        return $collection;
    }
    
    /**
     * @param int $id
     * @return Variend_Data_Collection
     */
    public function getUserMedia($id, $limit = 0) {
        try {
            $response = $this->getAPI()->getUserMedia($id, $limit);
        } catch (Exception $e) {
            Mage::logException($e);
			return array();
        }

        $collection = new Varien_Data_Collection();
        if (!isset($response->data) || !is_array($response->data)) {
            return $collection;
        }

        foreach ($response->data as $item) {
            if (!isset($item->images->low_resolution->url)) {
                continue;
            }

            $image = new Varien_Object();
            $image->setUrl($item->images->low_resolution->url);

            if (isset($item->caption->text)) {
                $image->setName($item->caption->text);
            }

            if (isset($item->link)) {
                $image->setLink($item->link);
            }

            $collection->addItem($image);
        }

        return $collection;
    }

    protected function getHelper() {
        return Mage::helper('neklo_instagram');
    }
}