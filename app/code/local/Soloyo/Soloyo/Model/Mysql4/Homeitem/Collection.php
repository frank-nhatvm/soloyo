<?php

class Soloyo_Soloyo_Model_Mysql4_Homeitem_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('soloyo/homeitem');
    }
}