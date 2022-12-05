<?php

class Soloyo_Productupload_Block_Adminhtml_Imageprint_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('imageprint_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('productupload')->__('Item Information'));
    }
    

    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label'     => Mage::helper('productupload')->__('Image print Information'),
            'title'     => Mage::helper('productupload')->__('Image print Information'),
            'content'   => $this->getLayout()
                                ->createBlock('productupload/adminhtml_imageprint_edit_tab_form')
                                ->toHtml(),
        ));
        return parent::_beforeToHtml();
    }
}