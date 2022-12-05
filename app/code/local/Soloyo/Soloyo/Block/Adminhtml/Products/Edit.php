<?php

class Soloyo_Soloyo_Block_Adminhtml_Products_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
        
        $this->_objectId = 'id';
        $this->_blockGroup = 'soloyo';
        $this->_controller = 'adminhtml_products';
        
        $this->_updateButton('save', 'label', Mage::helper('soloyo')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('soloyo')->__('Delete Item'));
        
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
        if (Mage::registry('homeproducts_data')
            && Mage::registry('homeproducts_data')->getId()
        ) {
            return Mage::helper('soloyo')->__("Edit Item '%s'",
                                                $this->htmlEscape(Mage::registry('homeproducts_data')->getName())
            );
        }
        return Mage::helper('soloyo')->__('Add Item');
    }
}