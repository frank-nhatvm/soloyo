<?php

class Soloyo_Diachigiaohang_Block_Adminhtml_Quanhuyen_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
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
        
        if (Mage::getSingleton('adminhtml/session')->getQuanhuyenData()) {
            $data = Mage::getSingleton('adminhtml/session')->getQuanhuyenData();
            Mage::getSingleton('adminhtml/session')->setQuanhuyenData(null);
        } elseif (Mage::registry('quanhuyen_data')) {
            $data = Mage::registry('quanhuyen_data')->getData();
        }
        $fieldset = $form->addFieldset('quanhuyen_form', array(
            'legend'=>Mage::helper('diachigiaohang')->__('Item information')
        ));

        $fieldset->addField('ten_quanhuyen', 'text', array(
            'label'        => Mage::helper('diachigiaohang')->__('Name'),
            'class'        => 'required-entry',
            'required'    => true,
            'name'        => 'ten_quanhuyen',
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

        $fieldset->addField('use_ship_xaphuong', 'select', array(
            'label'        => Mage::helper('diachigiaohang')->__('Use shipping cost of xaphuong'),
            'name'        => 'use_ship_xaphuong',
            'values'    => Mage::getSingleton('diachigiaohang/yesno')->getOptionHash(),
        ));

        $fieldset->addField('xaphuong_ids', 'multiselect', array(
            'name' => 'xaphuong_ids[]',
            'label' => Mage::helper('diachigiaohang')->__('Xa/phuong'),
            'title' => Mage::helper('diachigiaohang')->__('Xa/phuong'),

            'values' => Mage::helper('diachigiaohang')->getAllXaphuong(),
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