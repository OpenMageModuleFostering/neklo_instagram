<?php
/*
NOTICE OF LICENSE

This source file is subject to the NekloEULA that is bundled with this package in the file ICENSE.txt.

It is also available through the world-wide-web at this URL: http://store.neklo.com/LICENSE.txt

Copyright (c)  Neklo (http://store.neklo.com/)
*/

class Neklo_Instagram_Block_Widget_Feed extends Mage_Core_Block_Template implements Mage_Widget_Block_Interface
{ 
    protected function _construct() {
        $this->setCacheLifetime(Mage::helper('neklo_instagram')->getCacheLifetime());
        parent::_construct();
    }
    
    public function getImages() {
        switch ($this->getMode()) {
            case Neklo_Instagram_Helper_Data::INSTAGRAM_MODE_BY_USER_ID:
                if ($this->getUserId()) {
                    return Mage::getModel('neklo_instagram/instagram')->getUserMedia($this->getUserId(), $this->getLimitItems());
                }
                break;
            case Neklo_Instagram_Helper_Data::INSTAGRAM_MODE_BY_HASHTAG:
                if ($this->getHashtag()) {
                    return Mage::getModel('neklo_instagram/instagram')->getTagMedia(trim($this->getHashtag(), ' #'), $this->getLimitItems());
                }
                break;
            case Neklo_Instagram_Helper_Data::INSTAGRAM_MODE_BY_PRODUCT_HASHTAG:
                $product = Mage::registry('current_product');
                if ($product && $product->getInstagramHashtag()) {
                    $hashtag = trim($product->getInstagramHashtag(), ' #');
                    $this->setHashtag($hashtag);
                    return Mage::getModel('neklo_instagram/instagram')->getTagMedia($hashtag, $this->getLimitItems());
                }
                break;
        }
        return array();
    }
}