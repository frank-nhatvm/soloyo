<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 11/28/18
 * Time: 4:02 PM
 */
class Soloyo_Productupload_Block_Designer_Register extends Mage_Core_Block_Template{

    public function getRegisterUrl()
    {
        return Mage::getUrl('productupload/designer/register_post', array('_secure' => true));
    }
}