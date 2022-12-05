<?php

class Soloyo_Soloyo_Model_Homebanner extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('soloyo/homebanner');
    }

    public function getActiveBanners(){
        return $this->getCollection()->addFieldToFilter('status','1')->setOrder('position', 'ASC');
    }

}