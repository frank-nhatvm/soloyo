<?php

class Soloyo_Printer_Block_Adminhtml_Printer extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_printer';
        $this->_blockGroup = 'printer';
        $this->_headerText = Mage::helper('printer')->__('Printer Manager');
        $this->_addButtonLabel = Mage::helper('printer')->__('Add Printer');
        parent::__construct();
    }
}