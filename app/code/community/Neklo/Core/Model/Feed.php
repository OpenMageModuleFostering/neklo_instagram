<?php

class Neklo_Core_Model_Feed extends Mage_AdminNotification_Model_Feed
{
    const XML_USE_HTTPS_PATH    = 'neklo_core/admin_notification/use_https';
    const XML_FEED_URL_PATH     = 'neklo_core/admin_notification/feed_url';
    const XML_FREQUENCY_PATH    = 'neklo_core/admin_notification/frequency';

    const LAST_CHECK_CACHE_KEY  = 'neklo_core_admin_notifications_last_check';

    public function getFrequency()
    {
        return Mage::getStoreConfig(self::XML_FREQUENCY_PATH) * 3600;
    }

    public function getLastUpdate()
    {
        return Mage::app()->loadCache(self::LAST_CHECK_CACHE_KEY);
    }

    public function setLastUpdate()
    {
        Mage::app()->saveCache(time(), self::LAST_CHECK_CACHE_KEY);
        return $this;
    }

    public function getFeedUrl()
    {
        if (is_null($this->_feedUrl)) {
            $this->_feedUrl = (Mage::getStoreConfigFlag(self::XML_USE_HTTPS_PATH) ? 'https://' : 'http://') . Mage::getStoreConfig(self::XML_FEED_URL_PATH);
        }
        return $this->_feedUrl;
    }
}