<?php

class Soloyo_Campaign_Block_Adminhtml_Aff_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare tab form's information
     *
     * @return Soloyo_Campaign_Block_Adminhtml_Campaign_Edit_Tab_Form
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        
        if (Mage::getSingleton('adminhtml/session')->getAffData()) {
            $data = Mage::getSingleton('adminhtml/session')->getAffData();
            Mage::getSingleton('adminhtml/session')->setAffData(null);
        } elseif (Mage::registry('aff_data')) {
            $data = Mage::registry('aff_data')->getData();
        }
        $fieldset = $form->addFieldset('aff_form', array(
            'legend'=>Mage::helper('campaign')->__('Player information')
        ));

        $fieldset->addField('email', 'text', array(
            'label'        => Mage::helper('campaign')->__('email'),
            'class'        => 'required-entry',
            'required'    => true,
            'name'        => 'email',
        ));

        $fieldset->addField('face_id', 'text', array(
            'label'        => Mage::helper('campaign')->__('face_id'),
            'class'        => 'required-entry',
            'required'    => true,
            'name'        => 'face_id',
        ));

        $fieldset->addField('code', 'text', array(
            'label'        => Mage::helper('campaign')->__('code'),
            'class'        => 'required-entry',
            'required'    => true,
            'name'        => 'code',
        ));

        $fieldset->addField('name', 'text', array(
            'label'        => Mage::helper('campaign')->__('name'),
            'class'        => 'required-entry',
            'required'    => true,
            'name'        => 'name',
        ));



        $fieldset->addField('status', 'select', array(
            'label'        => Mage::helper('campaign')->__('Status'),
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
            1   => 'Register',
            2 => 'Shared'
        );
    }

}