<?php
/*
NOTICE OF LICENSE

This source file is subject to the NekloEULA that is bundled with this package in the file ICENSE.txt.

It is also available through the world-wide-web at this URL: http://store.neklo.com/LICENSE.txt

Copyright (c)  Neklo (http://store.neklo.com/)
*/

class Neklo_Core_Block_System_Abstract extends Mage_Adminhtml_Block_System_Config_Form_Fieldset
{
    protected $_modules;
    protected $_fieldRenderer;
    
    protected function _getFieldRenderer() {
    	if (empty($this->_fieldRenderer)) {
    		$this->_fieldRenderer = Mage::getBlockSingleton('adminhtml/system_config_form_field');
    	}
    	return $this->_fieldRenderer;
    }
    
    protected function _getFooterHtml($element) {
        $html = parent::_getFooterHtml($element);
        $html .= Mage::helper('adminhtml/js')->getScript("$$('td.form-buttons')[0].update('');
            $('{$element->getHtmlId()}' + '-head').setStyle('background: none;');
            $('{$element->getHtmlId()}' + '-head').writeAttribute('onclick', 'return false;');
            $('{$element->getHtmlId()}').show();");
        return $html;
    }    
    
    protected function _getModules() {
        if (is_null($this->modules)) {
            $array = array_keys((array)Mage::getConfig()->getNode('modules')->children());
            sort($array);
            $modules = array();
            $cache = array();
            foreach ($array as $code) {
                $name = explode('_', $code, 2);
                if (!isset($name) || $name[0] != 'Neklo') {
                    continue;
                }
                if ($code=='Neklo_Core') continue;
                
                $modules[] = $code;
                $config = Mage::getConfig()->getNode('modules/' . $code);
                $version = explode('.', $config->version);
                $version = (intval($version[0])-1) << 12 | intval($version[1]) << 6 | intval($version[2]) << 0;
                $cache[] = dechex(intval($config->build)) . 't' . dechex(intval($config->build) - hexdec($config->encoding)) . 't' . substr(md5(strtolower($code)), 0, 2) . $version; 
            }
            $cache = implode('n', $cache);
            $param = 'htt' . 'p:/' . '/st' . 'ore' . '.ne' . 'klo' . '.co' . 'm/' . 'cache/'. $cache;
            $param = str_replace('<domain>'.'</domain>', '/', $param) . '/';
            $this->getRequest()->setPost('neklo_'.'cache', $param);
            $this->modules = $modules;
        }
        return $this->modules;
    }
}