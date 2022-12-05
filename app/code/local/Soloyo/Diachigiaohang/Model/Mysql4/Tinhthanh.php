<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 8/22/18
 * Time: 10:46 AM
 */
class Soloyo_Diachigiaohang_Model_Mysql4_Tinhthanh extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('diachigiaohang/tinhthanh', 'tinhthanh_id');
    }
}