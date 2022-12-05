<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 8/17/18
 * Time: 11:07 PM
 */

class Soloyo_Brandmodel_Model_Mysql4_Modelmobile extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('brandmodel/modelmobile', 'modelmobile_id');
    }
}