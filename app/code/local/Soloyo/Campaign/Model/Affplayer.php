<?php

class Soloyo_Campaign_Model_Affplayer extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('campaign/affplayer');
    }
}