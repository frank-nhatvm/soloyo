<?php

class Soloyo_Diachigiaohang_Model_Yesno extends Varien_Object
{
    const STATUS_YES    = 1;
    const STATUS_NO    = 2;
    
    /**
     * get model option as array
     *
     * @return array
     */
    static public function getOptionArray()
    {
        return array(
            self::STATUS_YES    => Mage::helper('diachigiaohang')->__('Yes'),
            self::STATUS_NO   => Mage::helper('diachigiaohang')->__('No')
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