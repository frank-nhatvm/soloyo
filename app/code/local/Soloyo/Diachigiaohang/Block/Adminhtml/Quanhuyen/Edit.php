<?php

class Soloyo_Diachigiaohang_Block_Adminhtml_Quanhuyen_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
        
        $this->_objectId = 'id';
        $this->_blockGroup = 'diachigiaohang';
        $this->_controller = 'adminhtml_quanhuyen';
        
        $this->_updateButton('save', 'label', Mage::helper('diachigiaohang')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('diachigiaohang')->__('Delete Item'));
        
        $this->_addButton('saveandcontinue', array(
            'label'        => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'    => 'saveAndContinueEdit()',
            'class'        => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('diachigiaohang_content') == null)
                    tinyMCE.execCommand('mceAddControl', false, 'diachigiaohang_content');
                else
                    tinyMCE.execCommand('mceRemoveControl', false, 'diachigiaohang_content');
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
        if (Mage::registry('quanhuyen_data')
            && Mage::registry('quanhuyen_data')->getId()
        ) {
            return Mage::helper('diachigiaohang')->__("Edit Item '%s'",
                                                $this->htmlEscape(Mage::registry('quanhuyen_data')->getTitle())
            );
        }
        return Mage::helper('diachigiaohang')->__('Add Item');
    }
}