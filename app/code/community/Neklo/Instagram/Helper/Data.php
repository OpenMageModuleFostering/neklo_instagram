<?php
/*
NOTICE OF LICENSE

This source file is subject to the NekloEULA that is bundled with this package in the file ICENSE.txt.

It is also available through the world-wide-web at this URL: http://store.neklo.com/LICENSE.txt

Copyright (c)  Neklo (http://store.neklo.com/)
*/

class Neklo_Instagram_Helper_Data extends Mage_Core_Helper_Abstract
{
    const INSTAGRAM_MODE_BY_USER_ID = 1;
    const INSTAGRAM_MODE_BY_HASHTAG = 2;
    const INSTAGRAM_MODE_BY_PRODUCT_HASHTAG = 3;
    
    public function getClientId() {
        return (string)Mage::getStoreConfig('neklo_instagram/general/api_client_id');
    }
    
    public function getCacheLifetime() {
        return (int)Mage::getStoreConfig('neklo_instagram/general/cache_lifetime');
    }
}
	 