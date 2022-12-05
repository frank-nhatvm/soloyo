<?php

class Soloyo_Productupload_Block_Designer_Design extends Mage_Core_Block_Template
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

        $pager = $this->getLayout()->createBlock('page/html_pager', 'custom.pager');
        $pager->setAvailableLimit(array(5=>5,10=>10,20=>20));
        $pager->setCollection($this->getCollection());
        $this->setChild('pager', $pager);
        $this->getCollection()->load();
        return $this;

    }

    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    public function getRegisterUrl()
    {
        return Mage::getUrl('productupload/designer/register_post', array('_secure' => true));
    }

    public function getNewDesignUrl()
    {
        return Mage::getUrl('productupload/designer/newdesign', array('_secure' => true));
    }

    public function getCreateNewDesignUrl()
    {
        return Mage::getUrl('productupload/designer/creatNewDesign', array('_secure' => true));
    }

    public function getEditDesignUrl($id){
        return Mage::getUrl('productupload/designer/editdesign',array('id'=>$id,'_secure' => true));
    }

    public function getBrandModelUrl()
    {
        return  Mage::getUrl('brandmodel/index/brand_models', array('_secure' => true));
    }

    public function getDesigncases()
    {
        $current_designer = Mage::helper('productupload')->getDesigner();
        if ($current_designer && $current_designer->getId()) {
            $collection = Mage::getModel('productupload/productupload')->getCollection()
                ->addFieldToFilter('designer_id', $current_designer->getId())->setOrder('created_time','DESC');
            return $collection;
        }

        return null;
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

    public function getUpdateDesignUrl(){
        return Mage::getUrl('productupload/designer/updatedesign', array('_secure' => true));
    }

    public function getStatusDesign($status_code){
        if($status_code == 0){
            return 'Đang chờ phê duyệt';
        }
        else if($status_code == 1){
            return 'Đang bán.';
        }
        else if($status_code == 2){
            return 'Không được phê duyệt';
        }
        else if($status_code == 3){
            return 'Cần sửa lại';
        }
        return 'N/A';

    }


    public function getProductUrl($product_id){
        $product_collection = Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToFilter('entity_id', $product_id)
            ->addUrlRewrite();
        $product_url = $product_collection->getFirstItem()->getProductUrl();
        return $product_url;
    }


}