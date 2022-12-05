<?php

class Soloyo_Brandmodel_Block_Adminhtml_Requestbrand_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('brandmodel_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('brandmodel')->__('Request Information'));
    }
    
    /**
     * prepare before render block to html
     *
     * @return Soloyo_Casedesign_Block_Adminhtml_Casedesign_Edit_Tabs
     */
    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label'     => Mage::helper('brandmodel')->__('Request Information'),
            'title'     => Mage::helper('brandmodel')->__('Request Information'),
            'content'   => $this->getLayout()
                                ->createBlock('brandmodel/adminhtml_requestbrand_edit_tab_form')
                                ->toHtml(),
        ));
        return parent::_beforeToHtml();
    }
}