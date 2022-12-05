<?php


/**
 * Printer Observer Model
 * 
 * @category    Magestore
 * @package     Magestore_Printer
 * @author      Magestore Developer
 */
class Soloyo_Printer_Model_Observer
{
    /**
     * process controller_action_predispatch event
     *
     * @return Soloyo_Printer_Model_Observer
     */
    public function controllerActionPredispatch($observer)
    {
        $action = $observer->getEvent()->getControllerAction();
        return $this;
    }
}