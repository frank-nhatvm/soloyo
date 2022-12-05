<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 8/22/18
 * Time: 10:44 AM
 */
class Soloyo_Diachigiaohang_Model_Quanhuyen extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('diachigiaohang/quanhuyen');
    }
}