<?php


/**
 * Printer Index Controller
 * 
 * @category    Magestore
 * @package     Magestore_Printer
 * @author      Magestore Developer
 */
class Soloyo_Printer_IndexController extends Mage_Core_Controller_Front_Action
{
    /**
     * index action
     */
    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }
}