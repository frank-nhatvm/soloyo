<?php

class Soloyo_Soloyo_Model_Status extends Varien_Object
{
    const STATUS_ENABLED    = 1;
    const STATUS_DISABLED    = 0;
    

    static public function getOptionArray()
    {
        return array(
            self::STATUS_ENABLED    => Mage::helper('soloyo')->__('Enabled'),
            self::STATUS_DISABLED   => Mage::helper('soloyo')->__('Disabled')
        );
    }

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