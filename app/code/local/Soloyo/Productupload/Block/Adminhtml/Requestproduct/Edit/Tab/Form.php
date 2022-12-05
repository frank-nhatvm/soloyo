<?php

class Soloyo_Productupload_Block_Adminhtml_Requestproduct_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{

    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);

        if (Mage::getSingleton('adminhtml/session')->getRequestproductData()) {
            $data = Mage::getSingleton('adminhtml/session')->getRequestproductData();
            Mage::getSingleton('adminhtml/session')->setRequestproductData(null);
        } elseif (Mage::registry('requestproduct_data')) {
            $data = Mage::registry('requestproduct_data')->getData();
        }

        $fieldset = $form->addFieldset('productupload_form', array(
            'legend' => Mage::helper('productupload')->__('Item information')
        ));

        $fieldset->addField('designer_id', 'text', array(
            'label' => Mage::helper('productupload')->__('Designer ID'),
            'class' => 'required-entry',
            'name' => 'designer_id',
        ));


        $fieldset->addField('user_id', 'text', array(
            'label' => Mage::helper('productupload')->__('User Id'),
            'name' => 'user_id',
        ));

        $fieldset->addField('email', 'text', array(
            'label' => Mage::helper('productupload')->__('Email'),
            'name' => 'email',
        ));

        $fieldset->addField('phone', 'text', array(
            'label' => Mage::helper('productupload')->__('Phone'),
            'name' => 'phone',
        ));

        $fieldset->addField('product_id', 'text', array(
            'label' => Mage::helper('productupload')->__('Product Id'),
            'name' => 'product_id',
        ));

        $fieldset->addField('requirement', 'text', array(
            'label' => Mage::helper('productupload')->__('Requirement'),

            'name' => 'requirement',
        ));

        $fieldset->addField('status', 'select', array(
            'label' => Mage::helper('productupload')->__('Status'),
            'name' => 'status',
            'values' => $this->getOptionHash(),
        ));

        $form->setValues($data);
        return parent::_prepareForm();
    }


    public function getOptionHash()
    {
        $options = array();
        foreach ($this->getOptionArray() as $value => $label) {
            $options[] = array(
                'value'    => $value,
                'label'    => $label
            );
        }
        return $options;
    }

    public function getOptionArray()
    {
        return array(
            0 => 'Pending',
            1 => 'Sent to Designer',
            2 => 'Updated',
            3 => 'Cancel',
            4 => 'Designer not update',
        );
    }

}