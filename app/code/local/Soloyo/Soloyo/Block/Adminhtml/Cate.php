<?php

class Soloyo_Soloyo_Block_Adminhtml_Cate extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_cate';
        $this->_blockGroup = 'soloyo';
        $this->_headerText = Mage::helper('soloyo')->__('Category Manager');
        $this->_addButtonLabel = Mage::helper('soloyo')->__('Add Category');
        parent::__construct();
    }
}