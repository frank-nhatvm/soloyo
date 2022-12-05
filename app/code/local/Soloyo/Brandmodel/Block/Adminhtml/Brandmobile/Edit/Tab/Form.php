<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 8/18/18
 * Time: 9:13 AM
 */
class Soloyo_Brandmodel_Block_Adminhtml_Brandmobile_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{

    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);

        if (Mage::getSingleton('adminhtml/session')->getBrandmobileData()) {
            $data = Mage::getSingleton('adminhtml/session')->getBrandmobileData();
            Mage::getSingleton('adminhtml/session')->setBrandmobileData(null);
        } elseif (Mage::registry('brandmobile_data')) {
            $data = Mage::registry('brandmobile_data')->getData();
        }
        $fieldset = $form->addFieldset('brandmobile_form', array(
            'legend'=>Mage::helper('brandmodel')->__('Brand information')
        ));

        $fieldset->addField('brand_name', 'text', array(
            'label'        => Mage::helper('brandmodel')->__('Name'),
            'class'        => 'required-entry',
            'required'    => true,
            'name'        => 'brand_name',
        ));

        $fieldset->addField('status', 'select', array(
            'label'        => Mage::helper('brandmodel')->__('Status'),
            'name'        => 'status',
            'values'    => Mage::getSingleton('brandmodel/status')->getOptionHash(),
        ));

        $fieldset->addField('model_mobile_ids', 'multiselect', array(
            'name' => 'model_mobile_ids[]',
            'label' => Mage::helper('brandmodel')->__('Model'),
            'title' => Mage::helper('brandmodel')->__('Model'),

            'values' => Mage::getSingleton('brandmodel/modelmobile')->getAll(),
        ));

        $fieldset->addField('brand_attribute_id', 'select', array(
            'name' => 'brand_attribute_id',
            'label' => Mage::helper('brandmodel')->__('Brand attribute'),
            'title' => Mage::helper('brandmodel')->__('Brand attribute'),

            'values' => Mage::helper('brandmodel')->getAllBrandAttribute(),
        ));

        $form->setValues($data);
        return parent::_prepareForm();
    }
}