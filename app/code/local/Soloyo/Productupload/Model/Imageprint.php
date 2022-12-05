<?php

class Soloyo_Productupload_Model_Imageprint extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('productupload/imageprint');
    }
}