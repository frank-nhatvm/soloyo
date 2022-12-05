<?php

class Soloyo_Soloyo_Block_Adminhtml_Products_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('homeproducts_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('soloyo')->__('Product list Information'));
    }
    

    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label'     => Mage::helper('soloyo')->__('Product list Information'),
            'title'     => Mage::helper('soloyo')->__('Product list Information'),
            'content'   => $this->getLayout()
                                ->createBlock('soloyo/adminhtml_products_edit_tab_form')
                                ->toHtml(),
        ));
        return parent::_beforeToHtml();
    }
}