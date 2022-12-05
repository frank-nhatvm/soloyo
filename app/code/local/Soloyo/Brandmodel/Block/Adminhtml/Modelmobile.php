<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 8/18/18
 * Time: 9:10 AM
 */
class Soloyo_Brandmodel_Block_Adminhtml_Modelmobile extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_modelmobile';
        $this->_blockGroup = 'brandmodel';
        $this->_headerText = Mage::helper('brandmodel')->__('Model Manager');
        $this->_addButtonLabel = Mage::helper('brandmodel')->__('Add Model');
        parent::__construct();
    }
}