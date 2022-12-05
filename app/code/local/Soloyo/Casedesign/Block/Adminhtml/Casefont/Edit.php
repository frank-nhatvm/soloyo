<?php

class Soloyo_Casedesign_Block_Adminhtml_Casefont_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
        
        $this->_objectId = 'id';
        $this->_blockGroup = 'casedesign';
        $this->_controller = 'adminhtml_casefont';
        
        $this->_updateButton('save', 'label', Mage::helper('casedesign')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('casedesign')->__('Delete Item'));
        
        $this->_addButton('saveandcontinue', array(
            'label'        => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'    => 'saveAndContinueEdit()',
            'class'        => 'save',
        ), -100);


    }
    
    /**
     * get text to show in header when edit an item
     *
     * @return string
     */
    public function getHeaderText()
    {
        if (Mage::registry('casefont_data')
            && Mage::registry('casefont_data')->getId()
        ) {
            return Mage::helper('casedesign')->__("Edit Item '%s'",
                                                $this->htmlEscape(Mage::registry('casefont_data')->getTitle())
            );
        }
        return Mage::helper('casedesign')->__('Add Item');
    }
}