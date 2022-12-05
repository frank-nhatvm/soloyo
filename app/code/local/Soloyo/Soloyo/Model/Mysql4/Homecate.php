<?php

class Soloyo_Soloyo_Model_Mysql4_Homecate extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('soloyo/homecate', 'home_cate_id');
    }
}