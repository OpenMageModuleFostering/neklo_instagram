<?php
/*
NOTICE OF LICENSE

This source file is subject to the NekloEULA that is bundled with this package in the file ICENSE.txt.

It is also available through the world-wide-web at this URL: http://store.neklo.com/LICENSE.txt

Copyright (c)  Neklo (http://store.neklo.com/)
*/

class Neklo_Core_Block_System_Extensions extends Neklo_Core_Block_System_Abstract
{
    public function render(Varien_Data_Form_Element_Abstract $element) {
        $html = $this->_getHeaderHtml($element);
        $html .= '<tr><td colspan="2"><h4>' . $this->__('Installed Neklo Extensions') . '</h4></td></tr>';
        $html .= $this->_getContentHtml($element); 
        $html .= $this->_getFooterHtml($element);
        $html .= '<style>.installed-extensions td {padding: 4px;}</style>';
        return $html;
    }
    
    protected function _getContentHtml($fieldset) {
        $html = '<tr class="installed-extensions">';
        $modules = $this->_getModules();
        $count = count($modules);        
        
        $columns = 0;
        if ($count<6) {
            $columns = 5;
        } elseif ($count % 5 == 0) {
            $columns = 5;
        } elseif ($count % 4 == 0) {
            $columns = 4;
        } elseif ($count % 3 == 0) {
            $columns = 3;
        } elseif (($count + 1) % 5 == 0) {
            $columns = 5;
        } elseif (($count + 1) % 4 == 0) {
            $columns = 4;
        } elseif (($count + 1) % 3 == 0) {
            $columns = 3;
        } else {
            $columns = 4;
        }
        
        foreach ($modules as $index => $code) {
            if (($index % $columns)==0 && $index!=0) $html .= '</tr><tr class="installed-extensions">';
            $html .= '<td align="center">';
            
            $config = Mage::getConfig()->getNode('modules/' . $code);
            
            $name = ($config->extension_name ? $config->extension_name : $code);
            
            $imgUrl = Mage::app()->getRequest()->getParam('neklo_cache') . strtolower($code) . '.jpg';
            $img = '<img src="'. $imgUrl .'" alt="'. $name .'">';
            
            if ($config->url) {
                $url = 'htt' . 'p:/' . '/st' . 'ore' . '.ne' . 'klo' . '.co' . 'm/'. $config->url . '.html';
                $url = str_replace('<domain>'.'</domain>', '/', $url);
                $img = '<a href="'. $url .'" target="_blank">'. $img .'</a>';
            }
            
            $html .= $img . '<br>';
            $html .= $name . '<br>v' . $config->version;
            $html .= '</td>';
        }
        $html .= '</tr>';
        return $html;
    }
}
