<?php

class Neklo_Core_Block_System_Newsletter extends Mage_Adminhtml_Block_System_Config_Form_Fieldset
{
    protected function _getHeaderTitleHtml($element)
    {
        return '<div class="entry-edit-head collapseable"><a id="' . $element->getHtmlId() . '-head" href="#" style="background:none;">' . $element->getLegend() . '</a></div>';
    }
}