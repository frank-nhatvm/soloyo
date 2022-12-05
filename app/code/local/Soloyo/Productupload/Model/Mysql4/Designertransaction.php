<?php

class Soloyo_Productupload_Model_Mysql4_Designertransaction extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('productupload/designertransaction', 'designer_transaction_id');
    }
}