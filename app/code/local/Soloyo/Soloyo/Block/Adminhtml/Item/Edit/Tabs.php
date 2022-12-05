<?php

class Soloyo_Soloyo_Block_Adminhtml_Item_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('homeitem_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('soloyo')->__('Home Item Information'));
    }
    

    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label'     => Mage::helper('soloyo')->__('Product Item Information'),
            'title'     => Mage::helper('soloyo')->__('Product Item Information'),
            'content'   => $this->getLayout()
                                ->createBlock('soloyo/adminhtml_item_edit_tab_form')
                                ->toHtml(),
        ));
        return parent::_beforeToHtml();
    }
}