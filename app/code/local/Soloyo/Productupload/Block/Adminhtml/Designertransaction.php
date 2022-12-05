<?php

class Soloyo_Productupload_Block_Adminhtml_Designertransaction extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_designertransaction';
        $this->_blockGroup = 'productupload';
        $this->_headerText = Mage::helper('productupload')->__('Transaction Manager');
        $this->_addButtonLabel = Mage::helper('productupload')->__('Add Transaction');
        parent::__construct();
    }
}