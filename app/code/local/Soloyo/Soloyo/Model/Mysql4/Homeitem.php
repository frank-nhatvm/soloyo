<?php

class Soloyo_Soloyo_Model_Mysql4_Homeitem extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('soloyo/homeitem', 'homeitem_id');
    }
}