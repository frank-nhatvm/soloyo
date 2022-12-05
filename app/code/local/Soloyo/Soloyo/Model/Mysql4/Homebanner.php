<?php

class Soloyo_Soloyo_Model_Mysql4_Homebanner extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('soloyo/homebanner', 'banner_id');
    }
}