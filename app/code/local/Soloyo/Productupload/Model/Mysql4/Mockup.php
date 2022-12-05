<?php

class Soloyo_Productupload_Model_Mysql4_Mockup extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('productupload/mockup', 'mockup_id');
    }
}