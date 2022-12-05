<?php

class Soloyo_Campaign_Block_Adminhtml_Aff_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('aff_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('campaign')->__('Player Information'));
    }
    

    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label'     => Mage::helper('campaign')->__('Player Information'),
            'title'     => Mage::helper('campaign')->__('Player Information'),
            'content'   => $this->getLayout()
                                ->createBlock('campaign/adminhtml_aff_edit_tab_form')
                                ->toHtml(),
        ));
        return parent::_beforeToHtml();
    }
}