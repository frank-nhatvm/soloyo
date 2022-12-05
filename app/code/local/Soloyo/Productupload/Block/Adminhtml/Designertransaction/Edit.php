<?php


class Soloyo_Productupload_Block_Adminhtml_Designertransaction_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
        
        $this->_objectId = 'id';
        $this->_blockGroup = 'productupload';
        $this->_controller = 'adminhtml_designertransaction';
        
        $this->_updateButton('save', 'label', Mage::helper('productupload')->__('Save'));
        $this->_updateButton('delete', 'label', Mage::helper('productupload')->__('Delete'));
        
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
        if (Mage::registry('designertransaction_data')
            && Mage::registry('designertransaction_data')->getId()
        ) {
            return Mage::helper('productupload')->__("Edit Item '%s'",
                                                $this->htmlEscape(Mage::registry('designertransaction_data')->getId())
            );
        }
        return Mage::helper('productupload')->__('Add Item');
    }
}