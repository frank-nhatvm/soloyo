<?php

class Soloyo_Campaign_Block_Adminhtml_Aff_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
        
        $this->_objectId = 'id';
        $this->_blockGroup = 'campaign';
        $this->_controller = 'adminhtml_aff';
        
        $this->_updateButton('save', 'label', Mage::helper('campaign')->__('Save Player'));
        $this->_updateButton('delete', 'label', Mage::helper('campaign')->__('Delete Player'));
        
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
        if (Mage::registry('aff_data')
            && Mage::registry('aff_data')->getId()
        ) {
            return Mage::helper('campaign')->__("Edit Item '%s'",
                                                $this->htmlEscape(Mage::registry('aff_data')->getName())
            );
        }
        return Mage::helper('campaign')->__('Add Player');
    }
}