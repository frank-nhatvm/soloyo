<?php

class Soloyo_Diachigiaohang_Model_Mysql4_Xaphuong_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('diachigiaohang/xaphuong');
    }
}