<?php

class Soloyo_Brandmodel_Block_Adminhtml_Requestbrand_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{

    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        
        if (Mage::getSingleton('adminhtml/session')->getRequestbrandData()) {
            $data = Mage::getSingleton('adminhtml/session')->getRequestbrandData();
            Mage::getSingleton('adminhtml/session')->setRequestbrandData(null);
        } elseif (Mage::registry('requestbrand_data')) {
            $data = Mage::registry('requestbrand_data')->getData();
        }

        $fieldset = $form->addFieldset('caseart_form', array(
            'legend'=>Mage::helper('brandmodel')->__('Request information')
        ));

        $fieldset->addField('email', 'text', array(
            'label'        => Mage::helper('brandmodel')->__('Email'),
            'name'        => 'email',
        ));

        $fieldset->addField('phone', 'text', array(
            'label'        => Mage::helper('brandmodel')->__('Phone'),
            'name'        => 'phone',
        ));

        $fieldset->addField('requirement', 'text', array(
            'label'        => Mage::helper('brandmodel')->__('Requirement'),
            'name'        => 'requirement',
        ));



        $fieldset->addField('status', 'select', array(
            'label'        => Mage::helper('brandmodel')->__('Status'),
            'name'        => 'status',
            'values'    => $this->getOptionHash(),
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
            0   => 'Pending',
            1   => 'Updated',
            2 => 'Cancel'
        );
    }


}