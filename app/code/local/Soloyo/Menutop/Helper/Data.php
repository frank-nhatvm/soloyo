<?php



class Soloyo_Menutop_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function getAllCategory(){
        $cate_collection = Mage::getModel('catalog/category')
            ->getCollection()->addAttributeToSelect('name')->addFieldToFilter('is_active','1');
        $result = array();

        //echo json_encode($cate_collection->getData());
       // die('');
        foreach ($cate_collection as $cate){
            $item = array();
            $item['label'] = $cate->getName();
            $item['value'] = $cate->getId();
            $result[] = $item;

        }

        return$result;
    }
}