<?php


/**
 * Printer Resource Collection Model
 * 
 * @category    Magestore
 * @package     Magestore_Printer
 * @author      Magestore Developer
 */
class Soloyo_Printer_Model_Mysql4_Printer_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('printer/printer');
    }
}