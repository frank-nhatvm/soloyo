<?php

class Soloyo_Productupload_Block_Adminhtml_Designer extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_designer';
        $this->_blockGroup = 'productupload';
        $this->_headerText = Mage::helper('productupload')->__('Designer Manager');
        $this->_addButtonLabel = Mage::helper('productupload')->__('Add Designer');
        parent::__construct();
    }
}