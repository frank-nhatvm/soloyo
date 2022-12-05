<?php

class Soloyo_Casedesign_Block_Adminhtml_Caseorder_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('caseorder_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('casedesign')->__('Order Information'));
    }

    /**
     * prepare before render block to html
     *
     * @return Soloyo_Casedesign_Block_Adminhtml_Casedesign_Edit_Tabs
     */
    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label'     => Mage::helper('casedesign')->__('Order Information'),
            'title'     => Mage::helper('casedesign')->__('Order Information'),
            'content'   => $this->getLayout()
                ->createBlock('casedesign/adminhtml_caseorder_edit_tab_form')
                ->toHtml(),
        ));
        $this->addTab('orderitems_section', array(
            'label'	 => Mage::helper('productupload')->__('Order Items'),
            'title'	 => Mage::helper('productupload')->__('Order Items'),
            'class' => 'ajax',
            'url' => $this->getUrl('*/*/orderitems', array('_current' => true, 'case_order_id' => $this->getRequest()->getParam('id'))),
        ));
        return parent::_beforeToHtml();
    }
}