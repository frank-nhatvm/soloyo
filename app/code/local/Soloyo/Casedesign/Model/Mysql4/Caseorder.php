<?php

class Soloyo_Casedesign_Model_Mysql4_Caseorder extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('casedesign/caseorder', 'caseorder_id');
    }
}