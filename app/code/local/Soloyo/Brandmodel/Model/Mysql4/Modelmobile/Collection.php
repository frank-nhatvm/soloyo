<?php



class Soloyo_Brandmodel_Model_Mysql4_Modelmobile_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('brandmodel/modelmobile');
    }
}