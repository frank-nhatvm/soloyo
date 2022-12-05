<?php

class Soloyo_Soloyo_Block_Adminhtml_Banner extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_banner';
        $this->_blockGroup = 'soloyo';
        $this->_headerText = Mage::helper('soloyo')->__('Banner Manager');
        $this->_addButtonLabel = Mage::helper('soloyo')->__('Add Banner');
        parent::__construct();
    }
}