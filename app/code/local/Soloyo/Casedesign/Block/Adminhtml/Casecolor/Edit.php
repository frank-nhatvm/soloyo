<?php

class Soloyo_Casedesign_Block_Adminhtml_Casecolor_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
        
        $this->_objectId = 'id';
        $this->_blockGroup = 'casedesign';
        $this->_controller = 'adminhtml_casedesign';
        
        $this->_updateButton('save', 'label', Mage::helper('casedesign')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('casedesign')->__('Delete Item'));
        
        $this->_addButton('saveandcontinue', array(
            'label'        => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'    => 'saveAndContinueEdit()',
            'class'        => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('casedesign_content') == null)
                    tinyMCE.execCommand('mceAddControl', false, 'casedesign_content');
                else
                    tinyMCE.execCommand('mceRemoveControl', false, 'casedesign_content');
            }

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
        if (Mage::registry('casecolor_data')
            && Mage::registry('casecolor_data')->getId()
        ) {
            return Mage::helper('casedesign')->__("Edit Item '%s'",
                                                $this->htmlEscape(Mage::registry('casecolor_data')->getTitle())
            );
        }
        return Mage::helper('casedesign')->__('Add Item');
    }
}