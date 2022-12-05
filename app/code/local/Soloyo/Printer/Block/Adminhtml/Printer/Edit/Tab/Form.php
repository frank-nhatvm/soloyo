<?php

class Soloyo_Printer_Block_Adminhtml_Printer_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare tab form's information
     *
     * @return Soloyo_Printer_Block_Adminhtml_Printer_Edit_Tab_Form
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        
        if (Mage::getSingleton('adminhtml/session')->getPrinterData()) {
            $data = Mage::getSingleton('adminhtml/session')->getPrinterData();
            Mage::getSingleton('adminhtml/session')->setPrinterData(null);
        } elseif (Mage::registry('printer_data')) {
            $data = Mage::registry('printer_data')->getData();
        }
        $fieldset = $form->addFieldset('printer_form', array(
            'legend'=>Mage::helper('printer')->__('Item information')
        ));

        $fieldset->addField('email', 'text', array(
            'label'        => Mage::helper('printer')->__('Email'),
            'class'        => 'required-entry',
            'required'    => true,
            'name'        => 'email',
        ));

        $fieldset->addField('name', 'text', array(
            'label'        => Mage::helper('printer')->__('Name'),
            'class'        => 'required-entry',
            'required'    => true,
            'name'        => 'name',
        ));


        $fieldset->addField('address', 'text', array(
            'label'        => Mage::helper('printer')->__('Address'),
            'class'        => 'required-entry',
            'required'    => true,
            'name'        => 'address',
        ));



        $fieldset->addField('status', 'select', array(
            'label'        => Mage::helper('printer')->__('Status'),
            'name'        => 'status',
            'values'    => Mage::getSingleton('printer/status')->getOptionHash(),
        ));



        $form->setValues($data);
        return parent::_prepareForm();
    }
}