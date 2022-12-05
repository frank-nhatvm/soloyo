<?php

class Soloyo_Productupload_Block_Adminhtml_Imageprint_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{

    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);

        if (Mage::getSingleton('adminhtml/session')->getImageprintData()) {
            $data = Mage::getSingleton('adminhtml/session')->getImageprintData();
            Mage::getSingleton('adminhtml/session')->setImageprintData(null);
        } elseif (Mage::registry('imageprint_data')) {
            $data = Mage::registry('imageprint_data')->getData();
        }

        $fieldset = $form->addFieldset('productupload_form', array(
            'legend' => Mage::helper('productupload')->__('Image print information')
        ));

        $fieldset->addField('product_id', 'select', array(
            'label' => Mage::helper('productupload')->__('Product'),
            'class' => 'required-entry',
            'values' => $this->getProduct(),
            'name' => 'product_id',
        ));

        $image_product = '';

        if(isset($data['image_product']) && $data['image_product']){
            $path = Mage::helper('productupload')->getUrlImageProductUpload();
            $image_product = $path.$data['image_product'];
        }

        $fieldset->addField('image_product', 'file', array(
            'label' => Mage::helper('productupload')->__('Image for printing'),
            'required' => false,
            'name' => 'image_product',
            'after_element_html' => '<img src="' . $image_product . '"  style="width:56px" />'
        ));


        $form->setValues($data);
        return parent::_prepareForm();
    }

    protected function getProduct()
    {
        $collection = Mage::getModel('catalog/product')->getCollection()
            ->addFieldToFilter('casedesign','0')
            ->addAttributeToSelect('name');

        $result = array();
        $result[] = array('value' => 'null', 'label' => 'Select a product');
        foreach ($collection as $product) {
            $item = array();
            $item['value'] = $product->getId();
            $item['label'] = $product->getName();
            $result[] = $item;
        }

        return $result;

    }

}