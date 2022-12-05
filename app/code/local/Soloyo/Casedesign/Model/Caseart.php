<?php

class Soloyo_Casedesign_Model_Caseart extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('casedesign/caseart');
    }
}