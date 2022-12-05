<?php

class Soloyo_Campaign_Model_Mysql4_Affplayer extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('campaign/affplayer', 'player_id');
    }
}