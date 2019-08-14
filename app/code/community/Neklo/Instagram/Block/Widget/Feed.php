<?php
class Neklo_Instagram_Block_Widget_Feed extends Mage_Core_Block_Template implements Mage_Widget_Block_Interface
{
    const NEKLO_WIDGET_CACHE_KEY='NEKLO_WIDGET_INST_CACHE_KEY';

    protected function _construct()
    {
        $i = Mage::registry(self::NEKLO_WIDGET_CACHE_KEY);
        if (!isset($i)) {
            $i = 0;
        }
        {
            Mage::unregister(self::NEKLO_WIDGET_CACHE_KEY, $i);
            $i++;
        }
        Mage::register(self::NEKLO_WIDGET_CACHE_KEY, $i);

        $this->setCacheKey(self::NEKLO_WIDGET_CACHE_KEY . '_' . $i);

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