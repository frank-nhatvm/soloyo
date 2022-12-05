<?php

class Soloyo_Campaign_Block_Aff extends Mage_Core_Block_Template
{

    public function _prepareLayout()
    {
        return parent::_prepareLayout();
    }


    public function getDesignCateUrl(){
        $cate_model = Mage::getModel('catalog/category')->load('3');
        $cate_url = $cate_model->getUrl();
        return $cate_url;
    }

}