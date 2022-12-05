<?php

class Soloyo_Casedesign_Block_Adminhtml_Caseart extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_caseart';
        $this->_blockGroup = 'casedesign';
        $this->_headerText = Mage::helper('casedesign')->__('Item Manager');
        $this->_addButtonLabel = Mage::helper('casedesign')->__('Add Item');
        parent::__construct();
    }
}