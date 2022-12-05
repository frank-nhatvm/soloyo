<?php

class Soloyo_Brandmodel_Model_Mysql4_Requestbrand extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('brandmodel/requestbrand', 'requestbrand_id');
    }
}