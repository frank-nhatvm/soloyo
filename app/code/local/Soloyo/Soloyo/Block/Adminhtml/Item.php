<?php

class Soloyo_Soloyo_Block_Adminhtml_Item extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_item';
        $this->_blockGroup = 'soloyo';
        $this->_headerText = Mage::helper('soloyo')->__('Home Item Manager');
        $this->_addButtonLabel = Mage::helper('soloyo')->__('Add Home Item');
        parent::__construct();
    }
}