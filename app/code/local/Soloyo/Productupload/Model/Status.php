<?php

class Soloyo_Productupload_Model_Status extends Varien_Object
{
    const STATUS_PENDING    = 0;
    const STATUS_APPROVED    = 1;
    const STATUS_CANCEL = 2;
    const  STATUS_RE_EDIT = 3;

    const OPTION_YES = 1;
    const  OPTION_NO = 0;

    static public function getYesnoOptionArray(){
        return array(
            self::OPTION_YES    => Mage::helper('productupload')->__('Yes'),
            self::OPTION_NO   => Mage::helper('productupload')->__('No')
        );
    }

    static public function getYesnoOptionHash(){
        $options = array();
        foreach (self::getYesnoOptionArray() as $value => $label) {
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
            self::STATUS_PENDING    => Mage::helper('productupload')->__('Pending'),
            self::STATUS_APPROVED   => Mage::helper('productupload')->__('Approved'),
            self::STATUS_CANCEL   => Mage::helper('productupload')->__('Cancel'),
            self::STATUS_RE_EDIT   => Mage::helper('productupload')->__('Re edit'),
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