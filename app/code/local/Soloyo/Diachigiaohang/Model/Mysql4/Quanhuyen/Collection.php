<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 8/22/18
 * Time: 10:49 AM
 */

class Soloyo_Diachigiaohang_Model_Mysql4_Quanhuyen_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('diachigiaohang/quanhuyen');
    }
}