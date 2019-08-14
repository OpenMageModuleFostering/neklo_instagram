<?php

class Neklo_Instagram_Helper_Data extends Mage_Core_Helper_Abstract
{
    const INSTAGRAM_MODE_BY_USER_ID = 1;
    const INSTAGRAM_MODE_BY_HASHTAG = 2;
    const INSTAGRAM_MODE_BY_PRODUCT_HASHTAG = 3;

    public function getCacheLifetime()
    {
        return (int)Mage::getStoreConfig(
            'neklo_instagram/general/cache_lifetime'
        );
    }
}