<?php

class Soloyo_Casedesign_Model_Mysql4_Casecolor extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('casedesign/casecolor', 'color_id');
    }
}