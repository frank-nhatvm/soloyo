<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 8/17/18
 * Time: 11:01 PM
 */

class Soloyo_Brandmodel_Model_Modelmobile extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('brandmodel/modelmobile');
    }

    public function getAll(){

        $data = array();
        $collection = $this->getCollection()->addFieldToFilter('status','1');
        foreach ($collection as $entity){
            $item = array();
            $item['label'] = $entity->getModelName();
            $item['value'] = $entity->getBrandModelAttributeId();
            $data[] = $item;
        }

        return $data;
    }

    public function getAllOriginal(){

        $data = array();
        $collection = $this->getCollection()->addFieldToFilter('status','1');
        foreach ($collection as $entity){
            $data[$entity->getBrandModelAttributeId()] = $entity->getModelName();
        }

        return $data;
    }
}