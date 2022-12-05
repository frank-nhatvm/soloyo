<?php

class Soloyo_Productupload_Model_Mysql4_Productupload extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('productupload/productupload', 'productupload_id');
    }
}