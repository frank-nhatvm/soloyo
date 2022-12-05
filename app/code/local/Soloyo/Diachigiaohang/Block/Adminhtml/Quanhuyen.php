<?php

class Soloyo_Diachigiaohang_Block_Adminhtml_Quanhuyen extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_quanhuyen';
        $this->_blockGroup = 'diachigiaohang';
        $this->_headerText = Mage::helper('diachigiaohang')->__('Item Manager');
        $this->_addButtonLabel = Mage::helper('diachigiaohang')->__('Add Item');
        parent::__construct();
    }
}