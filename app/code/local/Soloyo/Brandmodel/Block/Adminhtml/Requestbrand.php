<?php

class Soloyo_Brandmodel_Block_Adminhtml_Requestbrand extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_requestbrand';
        $this->_blockGroup = 'brandmodel';
        $this->_headerText = Mage::helper('brandmodel')->__('Request brand Manager');
        $this->_addButtonLabel = Mage::helper('brandmodel')->__('Add Request');
        parent::__construct();
    }
}