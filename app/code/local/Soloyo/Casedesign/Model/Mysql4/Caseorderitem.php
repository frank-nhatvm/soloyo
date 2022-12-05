<?php

class Soloyo_Casedesign_Model_Mysql4_Caseorderitem extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('casedesign/caseorderitem', 'caseorder_item_id');
    }
}