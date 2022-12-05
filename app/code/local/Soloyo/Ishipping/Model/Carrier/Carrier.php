<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 10/9/18
 * Time: 1:56 PM
 */

class Soloyo_Ishipping_Model_Carrier_Carrier extends Mage_Shipping_Model_Carrier_Abstract
    implements Mage_Shipping_Model_Carrier_Interface
{
    protected $_code = 'soloyo_ishipping';

    public function getAllowedMethods()
    {
        return array('soloyo_ishipping'=>$this->getConfigData('name'));
    }


    public function collectRates(Mage_Shipping_Model_Rate_Request $request)
    {
        $result = Mage::getModel('shipping/rate_result');

        $region_id = $request->getDestRegionId();
        $city = $request->getDestCity();

        $tinh_thanh = Mage::getModel('diachigiaohang/tinhthanh')->load($region_id);

        $giavanchuyen = $tinh_thanh->getGiavanchuyen();
        if($tinh_thanh->getUseShipQuanhuyen())
        {
            $quan_huyen = Mage::getModel('diachigiaohang/quanhuyen')->load($city);
            $giavanchuyen = $quan_huyen->getGiavanchuyen();
        }

        $rate = Mage::getModel('shipping/rate_result_method');

        $rate->setCarrier($this->_code);
        $rate->setCarrierTitle($this->getConfigData('title'));
        $rate->setMethod($this->_code);
        $rate->setMethodTitle($this->getConfigData('name'));
        $rate->setPrice($giavanchuyen);
        $rate->setCost($giavanchuyen);

        $result->append($rate);

        return $result;
    }


}