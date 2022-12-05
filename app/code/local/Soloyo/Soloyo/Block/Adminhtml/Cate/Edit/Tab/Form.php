<?php

class Soloyo_Soloyo_Block_Adminhtml_Cate_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare tab form's information
     *
     * @return Soloyo_Home_Block_Adminhtml_Home_Edit_Tab_Form
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        
        if (Mage::getSingleton('adminhtml/session')->getHomecateData()) {
            $data = Mage::getSingleton('adminhtml/session')->getHomecateData();
            Mage::getSingleton('adminhtml/session')->setHomecateData(null);
        } elseif (Mage::registry('homecate_data')) {
            $data = Mage::registry('homecate_data')->getData();
        }

        $fieldset = $form->addFieldset('homecate_form', array(
            'legend'=>Mage::helper('soloyo')->__('Category information')
        ));

        $fieldset->addField('name', 'text', array(
            'label'        => Mage::helper('soloyo')->__('Name'),
            'class'        => 'required-entry',
            'required'    => true,
            'name'        => 'name',
        ));
        $fieldset->addField('cate_id', 'text', array(
            'label'        => Mage::helper('soloyo')->__('Category Id'),
            'class'        => 'required-entry',
            'required'    => true,
            'name'        => 'cate_id',
        ));

        $fieldset->addField('url', 'text', array(
            'label'        => Mage::helper('soloyo')->__('Url'),

            'name'        => 'url',
        ));

        $fieldset->addField('image', 'file', array(
            'label'        => Mage::helper('soloyo')->__('Image'),
            'required'    => false,
            'name'        => 'image',
        ));
        $fieldset->addField('position', 'text', array(
            'label'        => Mage::helper('soloyo')->__('Position'),
            'required'    => true,
            'name'        => 'position',
        ));

        $fieldset->addField('status', 'select', array(
            'label'        => Mage::helper('soloyo')->__('Status'),
            'name'        => 'status',
            'values'    => Mage::getSingleton('soloyo/status')->getOptionHash(),
        ));

        $form->setValues($data);
        return parent::_prepareForm();
    }
}