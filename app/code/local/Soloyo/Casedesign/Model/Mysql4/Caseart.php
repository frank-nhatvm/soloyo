<?php

class Soloyo_Casedesign_Model_Mysql4_Caseart extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('casedesign/caseart', 'art_id');
    }
}