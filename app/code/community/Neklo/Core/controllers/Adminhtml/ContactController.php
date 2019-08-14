<?php
/*
NOTICE OF LICENSE

This source file is subject to the NekloEULA that is bundled with this package in the file ICENSE.txt.

It is also available through the world-wide-web at this URL: http://store.neklo.com/LICENSE.txt

Copyright (c)  Neklo (http://store.neklo.com/)
*/

class Neklo_Core_Adminhtml_ContactController extends Mage_Adminhtml_Controller_Action {
    
    public function indexAction() {
        $result = array('error' => 0);
        try {
            $data = $this->getRequest()->getPost();
            $this->_sendContactEmail($data);
        } catch (Exception $e) {
            $result['message'] = $e->getMessage();
            $result['error'] = 1;
            $this->_ajaxResponse($result);
            return;
        }
        $result['message'] = $this->__("Thank you for your request.<br>We'll respond as soon as possible.<br>We'll send copy of your request to your email.");
        $this->_ajaxResponse($result);
    }

    protected function _isAllowed() {
        return Mage::getSingleton('admin/session')->isAllowed('system/config/neklo_core');
    }

    protected function _ajaxResponse($result = array()) {
        $this->getResponse()->setBody(Zend_Json::encode($result));
        return;
    }
    
    protected function _sendContactEmail($data) {
        $translate = Mage::getSingleton('core/translate');
        $translate->setTranslateInline(false);

        $emailModel = Mage::getModel('core/email_template');
        $subject = $data['subject'];
        $message = $data['message'];
        $reason  = isset($data['other_reason'])?$data['other_reason']:$data['reason'];
        $version = Mage::getVersion();
        $url     = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB);
        $sender  = array(
            'name' => strip_tags($data['name']),
            'email' => strip_tags($data['email'])
        );

        $emailModel->setDesignConfig(array('area'=>'admin'))
            ->sendTransactional(
                'neklo_contact_email_template',
                $sender,
                'support@neklo.com',
                'Neklo Contact',
                array(
                    'id'       => '0-0-0',
                    'reason'        => $reason,
                    'subject'	    => $subject,
                    'message'	    => $message,
                    'version'       => $version,
                    'url'           => $url,
                )
        );
        
        $emailModel->setDesignConfig(array('area'=>'admin'))
            ->sendTransactional(
                'neklo_contact_copy_email_template',
                $sender,
                strip_tags($data['email']),
                'Neklo Contact Copy',
                array(
                    'id'            => '0-0-0',
                    'reason'        => $reason,
                    'subject'	    => $subject,
                    'message'	    => $message,
                    'version'       => $version,
                    'url'           => $url
                )
        );
        
        $translate->setTranslateInline(true);
    }
        
}