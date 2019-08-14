<?php

class Neklo_Core_Model_Observer
{
    public function renderContact($observer)
    {
        Mage::getBlockSingleton('neklo_core/system_contact')->render(new Varien_Data_Form_Element_Fieldset);
    }

    public function checkUpdate(Varien_Event_Observer $observer)
    {
        if (Mage::getSingleton('admin/session')->isLoggedIn()) {
            Mage::getModel('neklo_core/feed')->checkUpdate();
        }
    }
}