<?php

class Soloyo_Diachigiaohang_Helper_Data extends Mage_Core_Helper_Abstract
{

    public function getAllTinhthanh(){
        $collection = Mage::getModel('diachigiaohang/tinhthanh')
            ->getCollection()->addFieldToFilter('status','1');
        $result = array();
        foreach ($collection as $entity){
            $item = array();
            $item['label'] = $entity->getTenTinhthanh();
            $item['value'] = $entity->getId();
            $result[] = $item;
        }
        return $result;
    }

    public function getAllQuanhuyen(){
        $collection = Mage::getModel('diachigiaohang/quanhuyen')
            ->getCollection()->addFieldToFilter('status','1');
        $result = array();
        foreach ($collection as $entity){
            $item = array();
            $item['label'] = $entity->getTenQuanhuyen();
            $item['value'] = $entity->getId();
            $result[] = $item;
        }
        return $result;
    }

    public function getAllXaphuong(){
        $collection = Mage::getModel('diachigiaohang/xaphuong')
            ->getCollection()->addFieldToFilter('status','1');
        $result = array();
        foreach ($collection as $entity){
            $item = array();
            $item['label'] = $entity->getTenXaphuong();
            $item['value'] = $entity->getId();
            $result[] = $item;
        }
        return $result;
    }

    public function getAllDiachigiaohang(){
        $collection = Mage::getModel('diachigiaohang/tinhthanh')
            ->getCollection()->addFieldToFilter('status','1');
        $result = array();
        foreach ($collection as $entity){

            $entity_id = $entity->getId();
            $item = array();
            $item['id'] = $entity_id;
            $item['name'] = $entity->getTenTinhthanh();
            $list_quanhuyen = $this->getQuanhuyen($entity_id);
            $item['list_quanhuyen'] = $list_quanhuyen;
            $result[] = $item;
        }
        return $result;
    }

    public function getQuanhuyen($tinhthanh_id){
        $collection =   Mage::getModel('diachigiaohang/quanhuyen')
            ->getCollection()->addFieldToFilter('status','1')->addFieldToFilter('tinhthanh_id',$tinhthanh_id);
        $result = array();
        foreach ($collection as $entity){

            $entity_id = $entity->getId();
            $item = array();
            $item['id'] = $entity_id;
            $item['name'] = $entity->getTenQuanhuyen();
            $list_xaphuong = $this->getXaphuong($entity_id);
            $item['list_xaphuong'] = $list_xaphuong;
            $result[] = $item;
        }
        return $result;
    }

    public function getXaphuong($quanhuyen_id){
        $collection = Mage::getModel('diachigiaohang/xaphuong')
            ->getCollection()->addFieldToFilter('status','1')->addFieldToFilter('quanhuyen_id',$quanhuyen_id);
        $result = array();
        foreach ($collection as $entity){
            $item = array();
            $item['id'] = $entity->getId();
            $item['name'] = $entity->getTenXaphuong();
            $result[] = $item;
        }
        return $result;
    }

}