<?php

class Soloyo_Productupload_Block_Adminhtml_Productupload_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{

    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);

        if (Mage::getSingleton('adminhtml/session')->getProductuploadData()) {
            $data = Mage::getSingleton('adminhtml/session')->getProductuploadData();
            Mage::getSingleton('adminhtml/session')->setProductuploadData(null);
        } elseif (Mage::registry('productupload_data')) {
            $data = Mage::registry('productupload_data')->getData();
        }

        $fieldset = $form->addFieldset('productupload_form', array(
            'legend' => Mage::helper('productupload')->__('Item information')
        ));


        if (isset($data['designer_id']) && $data['designer_id']) {
            $fieldset->addField('designer_id', 'text', array(
                'label' => Mage::helper('productupload')->__('Designer ID'),
                'class' => 'required-entry',
                'name' => 'designer_id',
            ));
        } else {
            $fieldset->addField('is_admin', 'select', array(
                'label' => Mage::helper('productupload')->__('Design by admin'),
                'name' => 'is_admin',
                'values' => Mage::getSingleton('productupload/status')->getYesnoOptionHash(),
            ));
        }


        $fieldset->addField('cate_id', 'select', array(
            'label' => Mage::helper('productupload')->__('Category Id'),
            'values' => Mage::helper('productupload')->getAllCategory(),
            'name' => 'cate_id',
        ));

        $fieldset->addField('cate_name', 'text', array(
            'label' => Mage::helper('productupload')->__('Category name by designer'),
            'name' => 'cate_name',
        ));

        $fieldset->addField('qty_sale', 'text', array(
            'label' => Mage::helper('productupload')->__('Qty sale'),

            'readonly' => true,
            'name' => 'qty_sale',
        ));

        $fieldset->addField('product_name', 'text', array(
            'label' => Mage::helper('productupload')->__('Product name'),

            'name' => 'product_name',
        ));

        $fieldset->addField('product_sku', 'text', array(
            'label' => Mage::helper('productupload')->__('Product sku'),

            'name' => 'product_sku',
        ));

        $fieldset->addField('url_key', 'text', array(
            'label' => Mage::helper('productupload')->__('Product url key'),

            'name' => 'url_key',
        ));

        $image_product = Mage::helper('productupload')->getUrlImageProductUpload(). $data['image_product'];
        $fieldset->addField('image_product', 'file', array(
            'label' => Mage::helper('productupload')->__('Mockup Image product'),
            'after_element_html' => '<img width="240px" src="'.$image_product.'" />',
            'name' => 'image_product',
        ));

        $image_print =  Mage::helper('productupload')->getUrlImageProductUpload(). $data['image_print'];
        $fieldset->addField('image_print', 'file', array(
            'label' => Mage::helper('productupload')->__('Image for printing'),
            'name' => 'image_print',
            'after_element_html' => '<embed  src="'.$image_print.'" /> <a href="'.$image_print.'">View fullimage </a'
        ));

        $fieldset->addField('brand_id', 'select', array(
            'label' => Mage::helper('productupload')->__('Brand'),
            'values' => Mage::getModel('brandmodel/brandmobile')->getAll(),
            'name' => 'brand_id',
        ));
        $fieldset->addField('model_id', 'select', array(
            'label' => Mage::helper('productupload')->__('Model'),
            'values' => Mage::getModel('brandmodel/modelmobile')->getAll(),
            'name' => 'model_id',
        ));


        $fieldset->addField('is_for_sale', 'select', array(
            'label' => Mage::helper('productupload')->__('For Reseller'),
            'name' => 'is_for_sale',
            'values' => Mage::getSingleton('productupload/status')->getYesnoOptionHash(),
        ));

        $fieldset->addField('sale_type', 'select', array(
            'label' => Mage::helper('productupload')->__('Sale type'),
            'name' => 'sale_type',
            'values' => array(
                1 => 'Direct sale',
                2 => 'Product sale'
            ),
        ));

        $fieldset->addField('price', 'text', array(
            'label' => Mage::helper('productupload')->__('Price'),

            'name' => 'price',
        ));

        $fieldset->addField('price_for_seller', 'text', array(
            'label' => Mage::helper('productupload')->__('Price for reseller'),


            'name' => 'price_for_seller',
        ));

        $fieldset->addField('short_description', 'textarea', array(
            'name' => 'short_description',
            'label' => Mage::helper('productupload')->__('Short Description'),
            'title' => Mage::helper('productupload')->__('Short Description'),
            'style' => 'width:300px; height:50px;',

            'required' => false,
        ));


        $fieldset->addField('description', 'textarea', array(
            'name' => 'description',
            'label' => Mage::helper('productupload')->__('Description'),
            'title' => Mage::helper('productupload')->__('Description'),
            'style' => 'width:300px; height:50px;',

            'required' => false,
        ));

        $fieldset->addField('admin_comment', 'text', array(
            'label' => Mage::helper('productupload')->__('Admin comment'),
            'name' => 'admin_comment',
        ));

        $fieldset->addField('status', 'select', array(
            'label' => Mage::helper('productupload')->__('Status'),
            'name' => 'status',
            'values' => Mage::getSingleton('productupload/status')->getOptionHash(),
        ));

        $form->setValues($data);
        return parent::_prepareForm();
    }
}