<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 8/17/18
 * Time: 11:00 PM
 */
class Soloyo_Brandmodel_Model_Brandmobile extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('brandmodel/brandmobile');
    }

    public function getAll(){

        $data = array();
        $collection = $this->getCollection()->addFieldToFilter('status','1');
        foreach ($collection as $entity){
            $item = array();
            $item['label'] = $entity->getBrandName();
            $item['value'] = $entity->getBrandAttributeId();
            $data[] = $item;
        }

        return $data;
    }

    public function getAllOriginal(){
        $data = array();
        $collection = $this->getCollection()->addFieldToFilter('status','1');
        foreach ($collection as $entity){

            $data[$entity->getBrandAttributeId()] = $entity->getBrandName();

        }

        return $data;
    }


}