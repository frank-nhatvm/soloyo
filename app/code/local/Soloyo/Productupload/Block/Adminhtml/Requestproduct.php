<?php

class Soloyo_Productupload_Block_Adminhtml_Requestproduct extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {

        $this->_controller = 'adminhtml_requestproduct';
        $this->_blockGroup = 'productupload';
        $this->_headerText = Mage::helper('productupload')->__('Request Product Manager');
        $this->_addButtonLabel = Mage::helper('productupload')->__('Add Request Product');
        parent::__construct();
    }
}