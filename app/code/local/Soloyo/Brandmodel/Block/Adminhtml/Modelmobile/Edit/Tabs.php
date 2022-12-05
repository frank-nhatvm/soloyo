<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 8/18/18
 * Time: 9:13 AM
 */
class Soloyo_Brandmodel_Block_Adminhtml_Modelmobile_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('modelmobile_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('brandmodel')->__('Model Information'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label'     => Mage::helper('brandmodel')->__('Model Information'),
            'title'     => Mage::helper('brandmodel')->__('Model Information'),
            'content'   => $this->getLayout()
                ->createBlock('brandmodel/adminhtml_modelmobile_edit_tab_form')
                ->toHtml(),
        ));
        return parent::_beforeToHtml();
    }
}