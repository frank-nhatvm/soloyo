<?php

class Soloyo_Casedesign_Block_Adminhtml_Casecolor_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
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
        
        if (Mage::getSingleton('adminhtml/session')->getCasecolorData()) {
            $data = Mage::getSingleton('adminhtml/session')->getCasecolorData();
            Mage::getSingleton('adminhtml/session')->setCasecolorData(null);
        } elseif (Mage::registry('casecolor_data')) {
            $data = Mage::registry('casecolor_data')->getData();
        }
        $fieldset = $form->addFieldset('casedesign_form', array(
            'legend'=>Mage::helper('casedesign')->__('Item information')
        ));

        $fieldset->addField('name', 'text', array(
            'label'        => Mage::helper('casedesign')->__('Name'),
            'class'        => 'required-entry',
            'required'    => true,
            'name'        => 'name',
        ));

        $fieldset->addField('color_code', 'text', array(
            'label'        => Mage::helper('casedesign')->__('Color code'),
            'required'    => false,
            'name'        => 'color_code',
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