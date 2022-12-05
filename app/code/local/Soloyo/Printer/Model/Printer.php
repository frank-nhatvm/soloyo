<?php


/**
 * Printer Model
 * 
 * @category    Magestore
 * @package     Magestore_Printer
 * @author      Magestore Developer
 */
class Soloyo_Printer_Model_Printer extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('printer/printer');
    }
}