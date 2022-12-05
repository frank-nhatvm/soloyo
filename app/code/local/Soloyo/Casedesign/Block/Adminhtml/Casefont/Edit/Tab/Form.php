<?php

class Soloyo_Casedesign_Block_Adminhtml_Casefont_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare tab form's information
     *
     * @return Soloyo_Casedesign_Block_Adminhtml_Casedesign_Edit_Tab_Form
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        
        if (Mage::getSingleton('adminhtml/session')->getCasefontData()) {
            $data = Mage::getSingleton('adminhtml/session')->getCasefontData();
            Mage::getSingleton('adminhtml/session')->setCasefontData(null);
        } elseif (Mage::registry('casefont_data')) {
            $data = Mage::registry('casefont_data')->getData();
        }
        $fieldset = $form->addFieldset('casefont_form', array(
            'legend'=>Mage::helper('casedesign')->__('Item information')
        ));

        $fieldset->addField('name', 'text', array(
            'label'        => Mage::helper('casedesign')->__('Name'),
            'class'        => 'required-entry',
            'required'    => true,
            'name'        => 'name',
        ));

        $fieldset->addField('font_url', 'file', array(
            'label'        => Mage::helper('casedesign')->__('Font url'),
            'required'    => true,
            'name'        => 'font_url',
        ));

        $fieldset->addField('status', 'select', array(
            'label'        => Mage::helper('casedesign')->__('Status'),
            'name'        => 'status',
            'values'    => Mage::getSingleton('casedesign/status')->getOptionHash(),
        ));

        $form->setValues($data);
        return parent::_prepareForm();
    }
}