<?php

class Soloyo_Soloyo_Model_Homeitem extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('soloyo/homeitem');
    }
}