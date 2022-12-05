<?php

class Soloyo_Diachigiaohang_Block_Adminhtml_Tinhthanh_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare tab form's information
     *
     * @return Soloyo_Diachigiaohang_Block_Adminhtml_Diachigiaohang_Edit_Tab_Form
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        
        if (Mage::getSingleton('adminhtml/session')->getTinhthanhData()) {
            $data = Mage::getSingleton('adminhtml/session')->getTinhthanhData();
            Mage::getSingleton('adminhtml/session')->setTinhthanhData(null);
        } elseif (Mage::registry('tinhthanh_data')) {
            $data = Mage::registry('tinhthanh_data')->getData();
        }
        $fieldset = $form->addFieldset('tinhthanh_form', array(
            'legend'=>Mage::helper('diachigiaohang')->__('Item information')
        ));

        $fieldset->addField('ten_tinhthanh', 'text', array(
            'label'        => Mage::helper('diachigiaohang')->__('Name'),
            'class'        => 'required-entry',
            'required'    => true,
            'name'        => 'ten_tinhthanh',
        ));

        $fieldset->addField('giavanchuyen', 'text', array(
            'label'        => Mage::helper('diachigiaohang')->__('Shipping cost'),
            'name'        => 'giavanchuyen',
        ));

        $fieldset->addField('can_shipping', 'select', array(
            'label'        => Mage::helper('diachigiaohang')->__('Can shipping'),
            'name'        => 'can_shipping',
            'values'    => Mage::getSingleton('diachigiaohang/yesno')->getOptionHash(),
        ));

        $fieldset->addField('use_ship_quanhuyen', 'select', array(
            'label'        => Mage::helper('diachigiaohang')->__('Use shipping cost of Quanhuyen'),
            'name'        => 'use_ship_quanhuyen',
            'values'    => Mage::getSingleton('diachigiaohang/yesno')->getOptionHash(),
        ));

        $fieldset->addField('quanhuyen_ids', 'multiselect', array(
            'name' => 'quanhuyen_ids[]',
            'label' => Mage::helper('diachigiaohang')->__('Quan/huyen'),
            'title' => Mage::helper('diachigiaohang')->__('Quan/huyen'),
            'values' => Mage::helper('diachigiaohang')->getAllQuanhuyen(),
        ));

        $fieldset->addField('status', 'select', array(
            'label'        => Mage::helper('diachigiaohang')->__('Status'),
            'name'        => 'status',
            'values'    => Mage::getSingleton('diachigiaohang/status')->getOptionHash(),
        ));



        $form->setValues($data);
        return parent::_prepareForm();
    }
}