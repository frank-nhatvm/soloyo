<?php

class Soloyo_Productupload_Block_Adminhtml_Designer_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('designer_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('productupload')->__('Item Information'));
    }
    

    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label'     => Mage::helper('productupload')->__('Designer Information'),
            'title'     => Mage::helper('productupload')->__('Designer Information'),
            'content'   => $this->getLayout()
                                ->createBlock('productupload/adminhtml_designer_edit_tab_form')
                                ->toHtml(),
        ));
        $this->addTab('productupload_section', array(
            'label'	 => Mage::helper('productupload')->__('Case design'),
            'title'	 => Mage::helper('productupload')->__('Case design'),
            'class' => 'ajax',
            'url' => $this->getUrl('*/*/casedesign', array('_current' => true, 'designer_id' => $this->getRequest()->getParam('id'))),
        ));

        $this->addTab('transaction_section', array(
            'label'	 => Mage::helper('productupload')->__('Transaction'),
            'title'	 => Mage::helper('productupload')->__('Transaction'),
            'class' => 'ajax',
            'url' => $this->getUrl('*/*/transaction', array('_current' => true, 'designer_id' => $this->getRequest()->getParam('id'))),
        ));
        return parent::_beforeToHtml();
    }
}