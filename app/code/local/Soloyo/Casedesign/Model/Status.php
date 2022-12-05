<?php
/**
 * Magestore
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Magestore.com license that is
 * available through the world-wide-web at this URL:
 * http://www.magestore.com/license-agreement.html
 * 
 * DISCLAIMER
 * 
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 * 
 * @category    Magestore
 * @package     Magestore_Casedesign
 * @copyright   Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license     http://www.magestore.com/license-agreement.html
 */

/**
 * Casedesign Status Model
 * 
 * @category    Magestore
 * @package     Magestore_Casedesign
 * @author      Magestore Developer
 */
class Soloyo_Casedesign_Model_Status extends Varien_Object
{
    const STATUS_ENABLED    = 1;
    const STATUS_DISABLED    = 2;

    const CASE_ORDER_STATUS_NOT_SEND = 0;
    const CASE_ORDER_STATUS_SEND = 1;
    const CASE_ORDER_STATUS_PRINTING = 2;
    const CASE_ORDER_STATUS_PRINTED = 3;

    static public function getCaseOrderStatusArray(){
        return array(
          self::CASE_ORDER_STATUS_NOT_SEND => Mage::helper('casedesign')->__('Not send'),
          self::CASE_ORDER_STATUS_SEND => Mage::helper('casedesign')->__('Send'),
          self::CASE_ORDER_STATUS_PRINTING => Mage::helper('casedesign')->__('Printing'),
          self::CASE_ORDER_STATUS_PRINTED => Mage::helper('casedesign')->__('Print'),
        );
    }

    static public function getCaseOrderStatusHash(){
        $options = array();
        foreach (self::getCaseOrderStatusArray() as $value => $label) {
            $options[] = array(
                'value'    => $value,
                'label'    => $label
            );
        }
        return $options;
    }

    /**
     * get model option as array
     *
     * @return array
     */
    static public function getOptionArray()
    {
        return array(
            self::STATUS_ENABLED    => Mage::helper('casedesign')->__('Enabled'),
            self::STATUS_DISABLED   => Mage::helper('casedesign')->__('Disabled')
        );
    }
    
    /**
     * get model option hash as array
     *
     * @return array
     */
    static public function getOptionHash()
    {
        $options = array();
        foreach (self::getOptionArray() as $value => $label) {
            $options[] = array(
                'value'    => $value,
                'label'    => $label
            );
        }
        return $options;
    }
}