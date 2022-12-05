<?php

class Soloyo_Productupload_Block_Designer_Rank extends Mage_Core_Block_Template
{

    public function __construct()
    {
        parent::__construct();
        $collection = $this->getRankDesigner();
        $this->setCollection($collection);
    }

    protected function getRankDesigner(){
        $collection = Mage::getModel('productupload/designer')->getCollection()
            ->addFieldToFilter('status','1')->addFieldToFilter('total_balance',array('gt'=>0))->setOrder('total_balance','DESC')->setPageSize(5)
            ->setCurPage(1);;


        return $collection;
    }

    public function getDesignerName($user_id){
        $user = Mage::getModel('customer/customer')->load($user_id);
        if($user && $user->getId()){
            return $user->getFirstname();
        }
        return '';
    }

    public function getDrawn($designer){
        $total_balance = $designer->getTotalBalance();
        $current_balance = $designer->getBalance();
        if($total_balance > $current_balance){
            return $total_balance - $current_balance;
        }
        return 0;
    }

    public function getUrlOfDesigner($desinger_id){
        $category = Mage::getModel('catalog/category')->load('18');
        $url = $category->getUrl().'?designer_id='.$desinger_id;
        return $url;
    }

}