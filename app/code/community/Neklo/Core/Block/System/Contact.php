<?php

class Neklo_Core_Block_System_Contact extends Neklo_Core_Block_System_Abstract
{
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $fields = array(
            array(
                'type'  => 'text',
                'name'  => 'name',
                'label' => $this->__('Contact Name'),
                'class' => 'required-entry',
            ),
            array(
                'type'  => 'text',
                'name'  => 'email',
                'label' => $this->__('Contact Email'),
                'class' => 'required-entry validate-email',
            ),
            array(
                'type'  => 'text',
                'name'  => 'subject',
                'label' => $this->__('Subject'),
                'class' => 'required-entry'),
            array(
                'type'     => 'select',
                'name'     => 'reason',
                'label'    => $this->__('Reason'),
                'values'   => $this->_getReasons(),
                'class'    => 'required-entry',
                'onchange' => 'NekloContact.toggleReason()',
            ),
            array(
                'type'     => 'text',
                'name'     => 'other_reason',
                'label'    => $this->__('Other Reason'),
                'class'    => 'required-entry',
                'onchange' => 'NekloContact.toggleReason()',
            ),
            array(
                'type'  => 'textarea',
                'name'  => 'message',
                'label' => $this->__('Message'),
                'class' => 'required-entry',
            ),
            array(
                'type'               => 'label',
                'name'               => 'send',
                'after_element_html' => '<div class="right"><button type="button" class="scalable save" onclick="NekloContact.submit()">' . $this->__('Send') . '</button></div><div class="notice" id="ajax-response"></div>',
            ),
        );
        if (!$element->getForm()) {
            return '';
        }
        $html = $this->_getHeaderHtml($element);
        foreach ($fields as $field) {
            $html .= $this->_getFieldHtml($element, $field);
        }
        $html .= $this->_getFooterHtml($element);
        return $html;
    }

    protected function _getReasons()
    {
        $modules = $this->_getModules();

        $reasons[] = array(
            'label' => $this->__('Please select'),
            'value' => ''
        );
        $reasons[] = array(
            'label' => $this->__('Magento Related Support (paid)'),
            'value' => 'Magento v' . Mage::getVersion()
        );
        $reasons[] = array(
            'label' => $this->__('Request New Extension Development (paid)'),
            'value' => 'New Extension'
        );
        foreach ($modules as $code) {
            $moduleConfig = Mage::getConfig()->getNode('modules/' . $code);
            $reasons[] = array(
                'label' => $this->__('%s Support (%s)', ($moduleConfig->extension_name ? $moduleConfig->extension_name : $code) . ' v' . $moduleConfig->version, ($moduleConfig->free ? $this->__('paid') : $this->__('free'))),
                'value' => $code . ' ' . $moduleConfig->version,
            );
        }
        $reasons[] = array(
            'label' => $this->__('Other Reason'),
            'value' => 'other',
        );
        return $reasons;
    }

    protected function _getFooterHtml($element)
    {
        $ajaxUrl = $this->getUrl('adminhtml/neklo_core_contact');
        $html = parent::_getFooterHtml($element);
        $html = '<h4>' . $this->__('Contact Neklo Support Team or visit <a href="%s" target="_blank">%s</a> for additional information', 'http://store.neklo.com/', 'store.neklo.com') . '</h4>' . $html;

        $html .= Mage::helper('adminhtml/js')->getScript(
            '
            var NekloContact = {
                toggleReason: function() {
                    if ($("reason").getValue() != "other"){
                        $("other_reason").up(1).hide();
                        $("other_reason").disable();
                    } else {
                        $("other_reason").enable();
                        $("other_reason").up(1).show();
                    }
                },
                submit: function() {
                    if (supportForm.validator.validate()){
                        new Ajax.Request(
                            "' . $ajaxUrl . '",
                            {
                                method: "post",
                                parameters: Form.serialize($("' . $element->getHtmlId() . '")),
                                onSuccess:function(transport){
                                    if (transport && transport.responseText){
                                        try {
                                            response = eval("(" + transport.responseText + ")");
                                        } catch (e) {
                                            response = {};
                                        }
                                    }
                                    
                                    if ((typeof response.message) == "string") {
                                        $("ajax-response").update(response.message);
                                    } else {
                                        $("ajax-response").update(response.message.join("<br/>"));
                                    }
                                    
                                    if (response.error==0) {
                                        $("subject").value = "";
                                        $("other_reason").value = "";
                                        $("message").value = "";
                                        $("reason").selectedIndex = 0;
                                    }
                                    
                                    new PeriodicalExecuter(function(pe){ $("ajax-response").update(""); pe.stop(); }, 20);
                                } 
                            }
                        );
                    }
                }
            };
            
            NekloContact.toggleReason();
            supportForm = new varienForm($(' . $element->getHtmlId() . '));
            '
        );
        return $html;
    }

    protected function _getFieldHtml($fieldset, $field)
    {
        $type = $field['type'];
        unset($field['type']);
        $field = $fieldset
            ->addField($field['name'], $type, $field)
            ->setRenderer($this->_getFieldRenderer())
        ;
        return $field->toHtml();
    }
}