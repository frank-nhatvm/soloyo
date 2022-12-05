<?php

class Soloyo_Productupload_Block_Adminhtml_Designertransaction_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{

    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);

        if (Mage::getSingleton('adminhtml/session')->getDesignertransactionData()) {
            $data = Mage::getSingleton('adminhtml/session')->getDesignertransactionData();
            Mage::getSingleton('adminhtml/session')->setDesignertransactionData(null);
        } elseif (Mage::registry('designertransaction_data')) {
            $data = Mage::registry('designertransaction_data')->getData();
        }


        $fieldset = $form->addFieldset('transaction_form', array(
            'legend' => Mage::helper('productupload')->__('Designer information')
        ));

        $fieldset->addType('datetime', 'Soloyo_Productupload_Block_Adminhtml_Renderer_Datetime');

        $fieldset->addField('name', 'link', array(
            'label'		=> Mage::helper('productupload')->__('Designer'),
            'name'     	=> 'name',
            'href' 		=> $this->getUrl('productuploadadmin/adminhtml_designer/edit', array('id' => $data['designer_id'])),
        ));

        $fieldset->addField('balance', 'text', array(
            'label' => Mage::helper('productupload')->__('Balance'),
            'name' => 'balance',
            'readonly' => true,
        ));

        $fieldset->addField('amount', 'text', array(
            'label' => Mage::helper('productupload')->__('Amount'),
            'name' => 'amount',
        ));

        $url_image_transaction = Mage::helper('productupload')->getUrlImageDesignerTransaction().$data['image_transaction'];
        $fieldset->addField('image_transaction', 'file', array(
            'label' => Mage::helper('productupload')->__('Image transaction'),
            'name' => 'image_transaction',
            'after_element_html' => '<img width="240px" src="'.$url_image_transaction.'" />'
        ));

        $fieldset->addField('admin_comment', 'textarea', array(
            'name' => 'admin_comment',
            'label' => Mage::helper('productupload')->__('Admin comment'),
            'title' => Mage::helper('productupload')->__('Admin comment'),
            'style' => 'width:300px; height:50px;',
            'required' => false,
        ));

        $fieldset->addField('designer_comment', 'textarea', array(
            'name' => 'designer_comment',
            'label' => Mage::helper('productupload')->__('Designer comment'),
            'title' => Mage::helper('productupload')->__('Designer comment'),
            'style' => 'width:300px; height:50px;',
            'required' => false,
        ));

        $fieldset->addField('status', 'select', array(
            'label' => Mage::helper('productupload')->__('Status'),
            'name' => 'status',
            'values' =>  array(
                0 => 'Pending',
                1 => 'Approved',
                2 => 'Cancel',
            ),
        ));

        $fieldset->addField('update_time', 'datetime', array(
            'label' => Mage::helper('productupload')->__('Update Date'),
            'bold' => true,
            'name' => 'update_time',
        ));

        $fieldset->addField('created_time', 'datetime', array(
            'label' => Mage::helper('productupload')->__('Sent Date'),
            'bold' => true,
            'name' => 'created_date',
        ));


        $form->setValues($data);
        return parent::_prepareForm();
    }
}