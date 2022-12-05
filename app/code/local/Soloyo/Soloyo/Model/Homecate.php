<?php

class Soloyo_Soloyo_Model_Homecate extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('soloyo/homecate');
    }

    public function getHomecate(){
        $collection = $this->getCollection()->addFieldToFilter('status','1')->setOrder('position', 'ASC');
        $result = array();

        foreach ($collection as $cate){
            $cate_item = $cate->toArray();
            if($cate_item['image'] ){
                $cate_item['image'] = Mage::helper('soloyo')->getUrlHomeCateImage().$cate_item['image'];
            }

            if($cate_item['cate_id']){
                $cate_item['url'] = Mage::helper('soloyo')->getUrlCate($cate_item['cate_id']);
            }
            $result[] = $cate_item;
        }
        return $result;
    }


}