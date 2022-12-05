<?php

class Soloyo_Soloyo_Block_Adminhtml_Banner_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
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
        
        if (Mage::getSingleton('adminhtml/session')->getHomebannerData()) {
            $data = Mage::getSingleton('adminhtml/session')->getHomebannerData();
            Mage::getSingleton('adminhtml/session')->setHomebannerData(null);
        } elseif (Mage::registry('homebanner_data')) {
            $data = Mage::registry('homebanner_data')->getData();
        }

        $fieldset = $form->addFieldset('homebanner_form', array(
            'legend'=>Mage::helper('soloyo')->__('Banner information')
        ));

        $fieldset->addField('name', 'text', array(
            'label'        => Mage::helper('soloyo')->__('Name'),
            'class'        => 'required-entry',
            'required'    => true,
            'name'        => 'name',
        ));

        $fieldset->addField('url', 'text', array(
            'label'        => Mage::helper('soloyo')->__('Url'),
            'class'        => 'required-entry',
            'required'    => true,
            'name'        => 'url',
        ));

        $fieldset->addField('image', 'file', array(
            'label'        => Mage::helper('soloyo')->__('Image'),
            'required'    => false,
            'name'        => 'image',
            'note'=> 'width: 1440 height 445'
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