<?php

class Soloyo_Productupload_Block_Adminhtml_Mockup_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{

    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);

        if (Mage::getSingleton('adminhtml/session')->getMockupData()) {
            $data = Mage::getSingleton('adminhtml/session')->getMockupData();
            Mage::getSingleton('adminhtml/session')->setMockupData(null);
        } elseif (Mage::registry('mockup_data')) {
            $data = Mage::registry('mockup_data')->getData();
        }


        $fieldset = $form->addFieldset('productupload_form', array(
            'legend' => Mage::helper('productupload')->__('Item information')
        ));

        $fieldset->addField('designer_id', 'text', array(
            'label' => Mage::helper('productupload')->__('Designer ID'),
            'name' => 'designer_id',
        ));


        $fieldset->addField('brand_id', 'select', array(
            'label' => Mage::helper('productupload')->__('Brand'),
            'name' => 'brand_id',
            'values' => Mage::getModel('brandmodel/brandmobile')->getAll(),
        ));

        $fieldset->addField('model_id', 'select', array(
            'label' => Mage::helper('productupload')->__('Model'),
            'name' => 'model_id',
            'values' => Mage::getModel('brandmodel/modelmobile')->getAll()
        ));

        $image_product = Mage::helper('productupload')->getUrlImageMockup(). $data['mockup_file'];
        $fieldset->addField('mockup_file', 'file', array(
            'label' => Mage::helper('productupload')->__('Mockup Image '),
            'after_element_html' => '<a href="'.$image_product.'"> View mockup image </a>',
            'name' => 'mockup_file',
        ));


        $fieldset->addField('status', 'select', array(
            'label' => Mage::helper('productupload')->__('Status'),
            'name' => 'status',
            'values' => $this->getOptionHashYesNo(),
        ));

        $fieldset->addField('is_recommend', 'select', array(
            'label' => Mage::helper('productupload')->__('Rcommend for designer'),
            'name' => 'is_recommend',
            'values' => $this->getOptionHashYesNo(),
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
            0 => 'Disable',
            1 => 'Enable',
        );
    }

    public function getOptionHashYesNo()
    {
        $options = array();
        foreach ($this->getOptionArrayYesNo() as $value => $label) {
            $options[] = array(
                'value'    => $value,
                'label'    => $label
            );
        }
        return $options;
    }

    public function getOptionArrayYesNo()
    {
        return array(
            0 => 'No',
            1 => 'Yes',
        );
    }



}