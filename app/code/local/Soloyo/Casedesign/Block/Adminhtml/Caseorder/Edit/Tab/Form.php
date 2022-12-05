<?php

class Soloyo_Casedesign_Block_Adminhtml_Caseorder_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare tab form's information
     *
     * @return Soloyo_Casedesign_Block_Adminhtml_Casedesign_Edit_Tab_Form
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);

        if (Mage::getSingleton('adminhtml/session')->getCaseorderData()) {
            $data = Mage::getSingleton('adminhtml/session')->getCaseorderData();
            Mage::getSingleton('adminhtml/session')->setCaseorderData(null);
        } elseif (Mage::registry('caseorder_data')) {
            $data = Mage::registry('caseorder_data')->getData();
        }

        $fieldset = $form->addFieldset('productupload_form', array(
            'legend' => Mage::helper('productupload')->__('Image print information')
        ));


        $fieldset->addField('order_id', 'text', array(
            'label' => Mage::helper('productupload')->__('Order ID'),
            'required' => false,
            'readonly' => true,
            'name' => 'order_id',
        ));

        $fieldset->addField('printer_id', 'select', array(
            'label' => Mage::helper('productupload')->__('Printer'),
            'required' => true,
            'name' => 'printer_id',
            'values' => $this->getListPrinters()
        ));


        $form->setValues($data);
        return parent::_prepareForm();
    }

    public function getCaseOrder(){
        if (Mage::getSingleton('adminhtml/session')->getCaseorderData()) {
            $data = Mage::getSingleton('adminhtml/session')->getCaseorderData();
            Mage::getSingleton('adminhtml/session')->setCaseorderData(null);
        } elseif (Mage::registry('caseorder_data')) {
            $data = Mage::registry('caseorder_data')->getData();
        }
        return $data;
    }

    protected function getListPrinters(){
        $collection = Mage::getModel('printer/printer')->getCollection()
            ->addFieldToFilter('status','1');

        $result = array();
        $result[] = ['value'=>0,'label'=>'Select a printer'];
        foreach ($collection as $printer){
            $item = array();
            $item['value'] = $printer->getId();
            $item['label'] = $printer->getName();
            $result[] = $item;
        }

        return $result;
    }

}