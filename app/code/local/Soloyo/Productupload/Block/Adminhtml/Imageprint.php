<?php

class Soloyo_Productupload_Block_Adminhtml_Imageprint extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_imageprint';
        $this->_blockGroup = 'productupload';
        $this->_headerText = Mage::helper('productupload')->__('Image Print Manager');
        $this->_addButtonLabel = Mage::helper('productupload')->__('Add Image Print');
        parent::__construct();
    }
}