<?php

class Soloyo_Brandmodel_Block_Adminhtml_Requestbrand_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
        
        $this->_objectId = 'id';
        $this->_blockGroup = 'brandmodel';
        $this->_controller = 'adminhtml_requestbrand';
        
        $this->_updateButton('save', 'label', Mage::helper('brandmodel')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('brandmodel')->__('Delete Item'));
        
        $this->_addButton('saveandcontinue', array(
            'label'        => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'    => 'saveAndContinueEdit()',
            'class'        => 'save',
        ), -100);

        $this->_formScripts[] = "

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }
    
    /**
     * get text to show in header when edit an item
     *
     * @return string
     */
    public function getHeaderText()
    {
        if (Mage::registry('requestbrand_data')
            && Mage::registry('requestbrand_data')->getId()
        ) {
            return Mage::helper('brandmodel')->__("Edit Item '%s'",
                                                $this->htmlEscape(Mage::registry('requestbrand_data')->getTitle())
            );
        }
        return Mage::helper('brandmodel')->__('Add Request');
    }
}