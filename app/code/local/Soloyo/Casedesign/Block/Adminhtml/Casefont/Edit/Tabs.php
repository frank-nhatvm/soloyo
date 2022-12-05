<?php

class Soloyo_Casedesign_Block_Adminhtml_Casefont_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('casefont_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('casedesign')->__('Item Information'));
    }
    
    /**
     * prepare before render block to html
     *
     * @return Soloyo_Casedesign_Block_Adminhtml_Casedesign_Edit_Tabs
     */
    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label'     => Mage::helper('casedesign')->__('Item Information'),
            'title'     => Mage::helper('casedesign')->__('Item Information'),
            'content'   => $this->getLayout()
                                ->createBlock('casedesign/adminhtml_casefont_edit_tab_form')
                                ->toHtml(),
        ));
        return parent::_beforeToHtml();
    }
}