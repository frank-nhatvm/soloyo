<?php

class Soloyo_Productupload_Block_Adminhtml_Designer_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{

    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);

        if (Mage::getSingleton('adminhtml/session')->getDesignerData()) {
            $data = Mage::getSingleton('adminhtml/session')->getDesignerData();
            Mage::getSingleton('adminhtml/session')->setDesignerData(null);
        } elseif (Mage::registry('designer_data')) {
            $data = Mage::registry('designer_data')->getData();
        }

        $fieldset = $form->addFieldset('productupload_form', array(
            'legend' => Mage::helper('productupload')->__('Designer information')
        ));

        $fieldset->addField('email', 'text', array(
            'label' => Mage::helper('productupload')->__('Email'),
            'class' => 'required-entry',
            'name' => 'email',
        ));
        $fieldset->addField('balance', 'text', array(
            'label' => Mage::helper('productupload')->__('Balance'),
            'class' => 'required-entry',
            'name' => 'balance',
        ));
        $fieldset->addField('bank_owner_name', 'text', array(
            'label' => Mage::helper('productupload')->__('Tên chủ thẻ'),
            'class' => 'required-entry',
            'name' => 'bank_owner_name',
        ));
        $fieldset->addField('bank_account_number', 'text', array(
            'label' => Mage::helper('productupload')->__('Số tài khoản'),
            'class' => 'required-entry',
            'name' => 'bank_account_number',
        ));
        $fieldset->addField('bank_name', 'text', array(
            'label' => Mage::helper('productupload')->__('Tên ngân hàng'),
            'class' => 'required-entry',
            'name' => 'bank_name',
        ));
        $fieldset->addField('bank_area', 'text', array(
            'label' => Mage::helper('productupload')->__('Chi nhánh'),
            'class' => 'required-entry',
            'name' => 'bank_area',
        ));

        $fieldset->addField('status', 'select', array(
            'label' => Mage::helper('productupload')->__('Status'),
            'name' => 'status',
            'values' => array(
                0 => 'Pending',
                1 => 'Approved',
                2 => 'Cancel',
                3 => 'Locked',
            ),
        ));



        $form->setValues($data);
        return parent::_prepareForm();
    }
}