<?php

class Soloyo_Brandmodel_Block_Brandmodel extends Mage_Core_Block_Template
{

    public function _prepareLayout()
    {
        return parent::_prepareLayout();
    }

    public function getCurrentCatUrl(){
        $cat_id = $this->getRequest()->getParam('cat_url');
        return$cat_id;
    }

    public function getCurrentCatId(){
        $cat_id = $this->getRequest()->getParam('cate_id');
        if(!$cat_id){
            $cat_id = '18';
        }
        return$cat_id;
    }
}