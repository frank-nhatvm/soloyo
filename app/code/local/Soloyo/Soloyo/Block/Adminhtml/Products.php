<?php

class Soloyo_Soloyo_Block_Adminhtml_Products extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_products';
        $this->_blockGroup = 'soloyo';
        $this->_headerText = Mage::helper('soloyo')->__('Product list Manager');
        $this->_addButtonLabel = Mage::helper('soloyo')->__('Add Product list');
        parent::__construct();
    }
}