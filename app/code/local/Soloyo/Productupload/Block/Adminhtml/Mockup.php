<?php

class Soloyo_Productupload_Block_Adminhtml_Mockup extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {

        $this->_controller = 'adminhtml_mockup';
        $this->_blockGroup = 'productupload';
        $this->_headerText = Mage::helper('productupload')->__('Mockup Manager');
        $this->_addButtonLabel = Mage::helper('productupload')->__('Mockup Product');
        parent::__construct();
    }
}