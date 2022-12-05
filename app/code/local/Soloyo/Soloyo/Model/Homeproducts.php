<?php

class Soloyo_Soloyo_Model_Homeproducts extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('soloyo/homeproducts');
    }

    public function getProductList(){
        $collection = $this->getCollection()->addFieldToFilter('status','1')->setOrder('position', 'ASC');
        $result = array();
        foreach ($collection as $products){
            $product_list = $products->toArray();
            if($product_list['image'] ){
                $product_list['image'] = Mage::helper('soloyo')->getUrlHomeProductsImage().$product_list['image'];
            }
            $product_list['items'] = $this->getItems($products->getId());
            $result[] = $product_list;
        }
        return $result;
    }

    protected function getItems($product_list_id){
        $items_collection = Mage::getModel('soloyo/homeitem')->getCollection()
            ->addFieldToFilter('parent_id',$product_list_id)->addFieldToFilter('status','1')->setOrder('position', 'ASC');

        $result = array();

        foreach ($items_collection as $item){
            $item_array = $item->toArray();

            if(!$item_array['type'] && !$item_array['url']){
                $product_id = $item_array['product_id'];
                $product = Mage::getModel('catalog/product')->load($product_id);
                $item_array['url'] = $product->getProductUrl();
            }
            $result[] = $item_array;
        }
        return $result;
    }
}