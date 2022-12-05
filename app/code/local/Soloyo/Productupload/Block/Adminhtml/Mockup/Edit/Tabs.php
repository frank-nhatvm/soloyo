<?php

class Soloyo_Productupload_Block_Adminhtml_Mockup_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('productupload_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('productupload')->__('Item Information'));
    }
    
    /**
     * prepare before render block to html
     *
     * @return Soloyo_Productupload_Block_Adminhtml_Productupload_Edit_Tabs
     */
    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label'     => Mage::helper('productupload')->__('Item Information'),
            'title'     => Mage::helper('productupload')->__('Item Information'),
            'content'   => $this->getLayout()
                                ->createBlock('productupload/adminhtml_mockup_edit_tab_form')
                                ->toHtml(),
        ));
        return parent::_beforeToHtml();
    }
}