<?php

class Soloyo_Productupload_Model_Mysql4_Designer extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('productupload/designer', 'designer_id');
    }
}