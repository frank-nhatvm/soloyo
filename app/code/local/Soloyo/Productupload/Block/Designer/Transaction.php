<?php

class Soloyo_Productupload_Block_Designer_Transaction extends Mage_Core_Block_Template
{

    public function __construct()
    {
        parent::__construct();

        $designer_id = $this->getCurrentDesigner()->getId();

        $collection = Mage::getModel('productupload/designertransaction')->getCollection()
            ->addFieldToFilter('designer_id', $designer_id);
        $this->setCollection($collection);
    }


    public function _prepareLayout()
    {
        parent::_prepareLayout();

        $pager = $this->getLayout()->createBlock('page/html_pager', 'custom.pager');
        $pager->setAvailableLimit(array(5 => 5, 10 => 10, 20 => 20));
        $pager->setCollection($this->getCollection());
        $this->setChild('pager', $pager);
        $this->getCollection()->load();
        return $this;

    }

    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }


    public function getCurrentDesigner()
    {
        $current_designer = Mage::helper('productupload')->getDesigner();
        if ($current_designer && $current_designer->getId()) {
            return $current_designer;
        }
        return null;
    }

    public function getDrawRequestUrl()
    {
        return Mage::getUrl('productupload/designer/drawRequest',array('_secure' => true));
    }

    public function getStatus($status_code){

        if($status_code == 0){
            return 'Đang chờ chuyển khoản';
        }
        else if($status_code == 1){
            return 'Đã chuyển khoản';
        }
        else if($status_code == 2){
            return 'Yêu cầu bị huỷ bỏ';
        }

        return 'N/A';

    }

}