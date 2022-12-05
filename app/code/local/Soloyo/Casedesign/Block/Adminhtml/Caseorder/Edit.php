<?php

class Soloyo_Casedesign_Block_Adminhtml_Caseorder_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
        
        $this->_objectId = 'id';
        $this->_blockGroup = 'casedesign';
        $this->_controller = 'adminhtml_casedesign';
        
        $this->_updateButton('save', 'label', Mage::helper('casedesign')->__('Send to printer'));
        $this->_updateButton('delete', 'label', Mage::helper('casedesign')->__('Delete Item'));

        $id = $this->getCaseOrderId();
        $download_url  = $this->getUrl('*/*/downloadShippingInfo',array('case_order_id'=>$id,'_current'=>true));


        $this->_addButton('saveandcontinue', array(
            'label'        => Mage::helper('adminhtml')->__('Print shipping infromation'),
            'onclick'    =>'setLocation(\'' . $download_url . '\')',
            'class'        => 'save',
        ), -100);


    }

    protected function getCaseOrderId(){
        if (Mage::registry('casedesign_data')
            && Mage::registry('casedesign_data')->getId()
        ){
            return Mage::registry('casedesign_data')->getId();
        }

        return '';
    }
    
    /**
     * get text to show in header when edit an item
     *
     * @return string
     */
    public function getHeaderText()
    {
        if (Mage::registry('casedesign_data')
            && Mage::registry('casedesign_data')->getId()
        ) {
            return Mage::helper('casedesign')->__("Edit Item '%s'",
                                                $this->htmlEscape(Mage::registry('casedesign_data')->getTitle())
            );
        }
        return Mage::helper('casedesign')->__('Add Item');
    }
}