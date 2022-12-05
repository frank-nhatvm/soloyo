<?php

class Soloyo_Menutop_Model_Menutop extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('menutop/menutop');
    }

    public function getMenuTopItems()
    {
        $menu = $this->getCollection()->addFieldToFilter('status', 1)->setOrder('menutop_position', 'ASC');
        $result = array();
        foreach ($menu as $item_menu) {
            $item = array();
            $item['name'] = $item_menu['menu_name'];
            $item['id'] = $item_menu['menutop_cat_id'];
            $item['url'] = $item_menu['menu_url'];
            $item['position'] = $item_menu['menutop_position'];

            $subCategories = Mage::getModel('catalog/category')->getCategories($item_menu['menutop_cat_id']);
            $subCate = array();
            foreach ($subCategories as $subCategory) {
                $subCateItem = array();
                $subCateItem['name'] = $subCategory['name'];
                $subCateItem['id'] = $subCategory['entity_id'];
                $subCateItem['position'] = $subCategory['position'];
                $cate_model = Mage::getModel('catalog/category')->load($subCategory['entity_id']);
                $cate_url = $cate_model->getUrl();
                $subCateItem['url'] = $cate_url;
                $subCate[] = $subCateItem;
            }
            $item['sub_cate'] = $subCate;
            $result[] = $item;
        }

        return $result;
//        $this->getResponse()->setHeader('Content-type', 'application/json');
//        return $this->getResponse()->setBody(json_encode($result));
    }

}