<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 8/18/18
 * Time: 9:14 AM
 */
class Soloyo_Brandmodel_Block_Adminhtml_Modelmobile_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{

    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);

        if (Mage::getSingleton('adminhtml/session')->getModelmobileData()) {
            $data = Mage::getSingleton('adminhtml/session')->getModelmobileData();
            Mage::getSingleton('adminhtml/session')->setModelmobileData(null);
        } elseif (Mage::registry('modelmobile_data')) {
            $data = Mage::registry('modelmobile_data')->getData();
        }
        $fieldset = $form->addFieldset('modelmobile_form', array(
            'legend'=>Mage::helper('brandmodel')->__('Model information')
        ));

        $fieldset->addField('model_name', 'text', array(
            'label'        => Mage::helper('brandmodel')->__('Name'),
            'class'        => 'required-entry',
            'required'    => true,
            'name'        => 'model_name',
        ));

        $fieldset->addField('brand_model_attribute_id', 'select', array(
            'name' => 'brand_model_attribute_id',
            'label' => Mage::helper('brandmodel')->__('Brand model attribute'),
            'title' => Mage::helper('brandmodel')->__('Brand model attribute'),

            'values' => Mage::helper('brandmodel')->getAllBrandModelAttribute(),
        ));

        $fieldset->addField('status', 'select', array(
            'label'        => Mage::helper('brandmodel')->__('Status'),
            'name'        => 'status',
            'values'    => Mage::getSingleton('brandmodel/status')->getOptionHash(),
        ));

        $form->setValues($data);
        return parent::_prepareForm();
    }
}