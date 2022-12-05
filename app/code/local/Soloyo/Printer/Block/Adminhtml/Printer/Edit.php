<?php

class Soloyo_Printer_Block_Adminhtml_Printer_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
        
        $this->_objectId = 'id';
        $this->_blockGroup = 'printer';
        $this->_controller = 'adminhtml_printer';
        
        $this->_updateButton('save', 'label', Mage::helper('printer')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('printer')->__('Delete Item'));
        
        $this->_addButton('saveandcontinue', array(
            'label'        => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'    => 'saveAndContinueEdit()',
            'class'        => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('printer_content') == null)
                    tinyMCE.execCommand('mceAddControl', false, 'printer_content');
                else
                    tinyMCE.execCommand('mceRemoveControl', false, 'printer_content');
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
        if (Mage::registry('printer_data')
            && Mage::registry('printer_data')->getId()
        ) {
            return Mage::helper('printer')->__("Edit Item '%s'",
                                                $this->htmlEscape(Mage::registry('printer_data')->getTitle())
            );
        }
        return Mage::helper('printer')->__('Add Item');
    }
}