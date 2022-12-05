<?php
class Soloyo_Casedesign_Model_Mysql4_Caseorder_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('casedesign/caseorder');
    }
}