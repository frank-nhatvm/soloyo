<?php

class Soloyo_Productupload_Block_Designer_Size extends Mage_Core_Block_Template
{

    public function __construct()
    {
        parent::__construct();
        $collection = $this->getDesigncases();
        $this->setCollection($collection);
    }

    public function _prepareLayout()
    {
        parent::_prepareLayout();

//        $pager = $this->getLayout()->createBlock('page/html_pager', 'custom.pager');
//        $pager->setAvailableLimit(array(5=>5,10=>10,20=>20));
//        $pager->setCollection($this->getCollection());
//        $this->setChild('pager', $pager);
//        $this->getCollection()->load();
        return $this;

    }

    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    public function getCurrentDesignerId()
    {
        $current_designer = Mage::helper('productupload')->getDesigner();
        if ($current_designer && $current_designer->getId()) {
            return $current_designer->getId();
        }
        return null;
    }

    public function getCurrentDesign(){
        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('productupload/productupload')->load($id);
        return $model;
    }



}