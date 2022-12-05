<?php

class Soloyo_Productupload_Block_Designer extends Mage_Core_Block_Template
{
    /**
     * prepare block's layout
     *
     * @return Soloyo_Productupload_Block_Productupload
     */
    public function _prepareLayout()
    {
        return parent::_prepareLayout();
    }


    public function getUpdateUrl(){
        return Mage::getUrl('productupload/designer/update_info',array('_secure'=>true));
    }

}