<?php

class Soloyo_Productupload_Block_Requestproduct extends Mage_Core_Block_Template
{

    public function _prepareLayout()
    {
        return parent::_prepareLayout();
    }

    public function getProductId()
    {
        return $this->getRequest()->getParam('product_id');
    }

    public function getDesignerId()
    {
        return $this->getRequest()->getParam('designer_id');
    }

    public function isLoggedIn()
    {
        return $this->_getSession()->isLoggedIn();
    }


    public function getUserId(){
        return $this->_getSession()->getCustomer()->getId();
    }

    public function getUserEmail(){
        return $this->_getSession()->getCustomer()->getEmail();
    }

    protected function _getSession()
    {
        return Mage::getSingleton('customer/session');
    }


}