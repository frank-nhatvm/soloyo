<?php

class Soloyo_Productupload_Model_Mysql4_Requestproduct extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('productupload/requestproduct', 'requestproduct_id');
    }
}