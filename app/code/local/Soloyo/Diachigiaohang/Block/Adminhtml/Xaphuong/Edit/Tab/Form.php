<?php

class Soloyo_Diachigiaohang_Block_Adminhtml_Xaphuong_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
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
        
        if (Mage::getSingleton('adminhtml/session')->getXaphuongData()) {
            $data = Mage::getSingleton('adminhtml/session')->getXaphuongData();
            Mage::getSingleton('adminhtml/session')->setXaphuongData(null);
        } elseif (Mage::registry('xaphuong_data')) {
            $data = Mage::registry('xaphuong_data')->getData();
        }
        $fieldset = $form->addFieldset('xaphuong_form', array(
            'legend'=>Mage::helper('diachigiaohang')->__('Item information')
        ));

        $fieldset->addField('ten_xaphuong', 'text', array(
            'label'        => Mage::helper('diachigiaohang')->__('Name'),
            'class'        => 'required-entry',
            'required'    => true,
            'name'        => 'ten_xaphuong',
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

        $fieldset->addField('status', 'select', array(
            'label'        => Mage::helper('diachigiaohang')->__('Status'),
            'name'        => 'status',
            'values'    => Mage::getSingleton('diachigiaohang/status')->getOptionHash(),
        ));


        $form->setValues($data);
        return parent::_prepareForm();
    }
}