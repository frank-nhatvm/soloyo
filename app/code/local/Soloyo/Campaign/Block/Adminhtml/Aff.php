<?php

class Soloyo_Campaign_Block_Adminhtml_Aff extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_aff';
        $this->_blockGroup = 'campaign';
        $this->_headerText = Mage::helper('campaign')->__('Player Manager');
        $this->_addButtonLabel = Mage::helper('campaign')->__('Add Player');
        parent::__construct();
    }
}