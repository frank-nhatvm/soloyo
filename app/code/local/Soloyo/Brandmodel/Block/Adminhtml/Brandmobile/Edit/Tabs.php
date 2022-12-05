<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 8/18/18
 * Time: 9:12 AM
 */
class Soloyo_Brandmodel_Block_Adminhtml_Brandmobile_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('brandmobile_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('brandmodel')->__('Brand Information'));
    }


    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label'     => Mage::helper('brandmodel')->__('Brand Information'),
            'title'     => Mage::helper('brandmodel')->__('Brand Information'),
            'content'   => $this->getLayout()
                ->createBlock('brandmodel/adminhtml_brandmobile_edit_tab_form')
                ->toHtml(),
        ));
        return parent::_beforeToHtml();
    }
}