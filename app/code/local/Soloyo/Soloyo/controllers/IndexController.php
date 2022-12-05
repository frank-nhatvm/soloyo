<?php

class Soloyo_Soloyo_IndexController extends Mage_Core_Controller_Front_Action
{

    public function test_cateAction(){
        $cate_id = $this->getRequest()->getParam('id');
        $cate_model = Mage::getModel('catalog/category')->load($cate_id);

        echo 'base url '. Mage::getBaseUrl( Mage_Core_Model_Store::URL_TYPE_WEB, true ).$cate_model->getData('url_path');
    }

    /**
     * index action
     */
    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function get_home_product_listAction(){

        $result = Mage::getModel('soloyo/homeproducts')->getProductList();

        $this->getResponse()->setHeader('Content-type', 'application/json');
        return $this->getResponse()->setBody(json_encode($result));
    }

    public function get_home_cateAction(){
        $result = Mage::getModel('soloyo/homecate')->getHomecate();

        $this->getResponse()->setHeader('Content-type', 'application/json');
        return $this->getResponse()->setBody(json_encode($result));
    }


    public function testAction(){
        $order = Mage::getModel('sales/order')->load('219');
        $shipping_address = $order->getShippingAddress();

        echo json_encode($shipping_address->getData());

    }

    public function getRecentlyProductAction()
    {
        $collection = Mage::getResourceModel('catalog/product_collection')
            ->addAttributeToSelect(
                Mage::getSingleton('catalog/config')
                    ->getProductAttributes()
            )
            ->addFieldToFilter('casedesign','0')
            ->addUrlRewrite();




        $collection->setOrder('created_at', 'desc');
        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($collection);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($collection);
        $collection->setPageSize(10);
        $items = array();

        foreach ($collection as $product) {
            $item = array();
            $product_id = $product->getId();
            $item['id'] = $product_id;
            $item['name'] = $product->getName();
            $imageHelper = Mage::helper('catalog/image');
            $thumbnail_image = $imageHelper->init($product, 'thumbnail')->resize(500, 500)->__toString();
            $item['image'] = $thumbnail_image;
            $item['url'] = $product->getProductUrl();
            $items[] = $item;

        }

        $this->getResponse()->setHeader('Content-type', 'application/json');
        return $this->getResponse()->setBody(json_encode($items));
    }

}