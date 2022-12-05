<?php

class Soloyo_Casedesign_Model_Mysql4_Casefont extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('casedesign/casefont', 'font_id');
    }
}