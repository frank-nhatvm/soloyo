<?php


/**
 * Printer Resource Model
 * 
 * @category    Magestore
 * @package     Magestore_Printer
 * @author      Magestore Developer
 */
class Soloyo_Printer_Model_Mysql4_Printer extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('printer/printer', 'printer_id');
    }
}