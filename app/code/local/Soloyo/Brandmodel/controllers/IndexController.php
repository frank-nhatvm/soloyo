<?php

class Soloyo_Brandmodel_IndexController extends Mage_Core_Controller_Front_Action
{

    public function get_menu_top_itemsAction()
    {
        $menu = Mage::getModel('menutop/menutop')->getCollection()
            ->addFieldToFilter('status', 1)->setOrder('menutop_position', 'ASC');;
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
        $this->getResponse()->setHeader('Content-type', 'application/json');
        return $this->getResponse()->setBody(json_encode($result));
    }

    public function update_specialpriceAction()
    {
        $collection = Mage::getResourceModel('catalog/product_collection');
        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($collection);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($collection);

        Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
        foreach ($collection as $item) {
            try {
                $product = Mage::getModel('catalog/product')->load($item->getId());
                //if ($product->getCasedesign()) {
                    $product->setSpecialPrice(null);
                //}


//                $product->setSpecialFromDate('2019-01-08');
//                $product->setSpecialFromDateIsFormated(true);
//
//                $product->setSpecialToDate('2019-01-25');
//                $product->setSpecialToDateIsFormated(true);

                $product->save();
            } catch (Exception $e) {
                echo 'exception ' . $e->getMessage();
                echo '<br/>';
            }

        }
        echo 'done';
    }

    public function getOrderAction()
    {
        $order_id = $this->getRequest()->getParam('order_id');
        $order = Mage::getModel('sales/order')->load($order_id);

        $result = $order->toArray();
        $order_items = $order->getItemsCollection();
        $items_order = array();
        foreach ($order_items as $order_item) {
            $item = $order_item->toArray();
            $options = $order_item->getProductOptions();
            $customOptions = $options['options'];
            $item['options'] = $customOptions;
            $items_order[] = $item;
        }
        $result['items_order'] = $items_order;
        echo json_encode($result);
    }

    public function test_send_emailAction()
    {
        try {
            $emailTemplate = Mage::getModel('core/email_template')->loadByCode('1');
            $vars = array('name' => 'test name', 'product_url' => 'soloyo.vn', 'product_name' => 'Op ung', 'message' => 'Xem ngay di');
            $emailTemplate->getProcessedTemplate($vars);

            $emailTemplate->setSenderName('Soloyo');
            $store_id = Mage::app()->getStore()->getId();
            $sender_email = Mage::getStoreConfig('trans_email/ident_general/email', $store_id);

            $emailTemplate->setSenderEmail($sender_email);
            $sender_name = Mage::getStoreConfig('trans_email/ident_general/name', $store_id);
            $emailTemplate->setSenderName($sender_name);

            $printer_id = $this->getRequest()->getParam('printer_id');
            $printer = Mage::getModel('printer/printer')->load($printer_id);
            $printer_email = $printer->getEmail();
            $printer_name = $printer->getName();
            $emailTemplate->send($printer_email, $printer_name, $vars);

            echo 'Send Success sendder email ' . $sender_email . ' sender name ' . $sender_name . 'printer email ' . $printer_email . ' printer name ' . $printer_name;
        } catch (Exception $e) {
            echo 'Send Fail ' . $e->getMessage();
        }
    }

    public function indexAction()
    {
        if (Mage::getModel('core/session')->getBrandId() && $cate_id = $this->getRequest()->getParam('cate_id')) {
            $this->open_cate($cate_id);
        } else {
            $this->loadLayout();
            $this->renderLayout();
        }

    }

    public function brand_modelsAction()
    {
        $result = array();

        $brand_collection = Mage::getModel('brandmodel/brandmobile')
            ->getCollection()->addFieldToFilter('status', '1');

        foreach ($brand_collection as $brand) {
            $item = array();
            $item['brand_name'] = $brand->getBrandName();
            $item['brand'] = $brand->getBrandAttributeId();
            $brand_id = $brand->getId();
            $item['list_model'] = $this->get_model_for_brand($brand_id);
            $result[] = $item;
        }

        $this->getResponse()->setHeader('Content-type', 'application/json');
        return $this->getResponse()->setBody(json_encode($result));
    }

    protected function get_model_for_brand($brand_id)
    {
        $model_collection = Mage::getModel('brandmodel/modelmobile')
            ->getCollection()->addFieldToFilter('status', '1')->addFieldToFilter('brandmobile_id', $brand_id);

        $result = array();
        foreach ($model_collection as $model) {
            $item = array();
            $item['model_name'] = $model->getModelName();
            $item['brand_model'] = $model->getBrandModelAttributeId();
            $result[] = $item;
        }

        return $result;
    }

    public function openCateAction()
    {


        $cate_id = $this->getRequest()->getParam('cate_id');

        if (!$cate_id) {
            $cate_id = '18';
        }

        $brand_id = $this->getRequest()->getParam('brand_id');
        $brand_name = $this->getRequest()->getParam('brand_name');
        $model_id = $this->getRequest()->getParam('model_id');
        $model_name = $this->getRequest()->getParam('model_name');


        Mage::getModel('core/session')->setData('cate_id', $cate_id);
        Mage::getModel('core/session')->setData('brand_id', $brand_id);
        Mage::getModel('core/session')->setData('brand_name', $brand_name);
        Mage::getModel('core/session')->setData('model_id', $model_id);
        Mage::getModel('core/session')->setData('model_name', $model_name);

        $this->open_cate($cate_id);


    }

    protected function open_cate($cate_id)
    {
        $cate_model = Mage::getModel('catalog/category')->load($cate_id);
        $cate_url = $cate_model->getUrl();
        $this->getResponse()->setRedirect($cate_url);
    }

    public function refreshAction()
    {
        Mage::getModel('core/session')->unsetData('cate_id');
        Mage::getModel('core/session')->unsetData('brand_id');
        Mage::getModel('core/session')->unsetData('brand_name');
        Mage::getModel('core/session')->unsetData('model_id');
        Mage::getModel('core/session')->unsetData('model_name');

        $this->_redirect('*/*/', array('cate_id' => $this->getRequest()->getParam('cate_id')));

    }

    public function requestbrandAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function requestbrand_postAction()
    {

        $data = $this->getRequest()->getPost();
        $result = array();

        if ($data && $this->checkRequestBrandData($data)) {
            $request_brand_model = Mage::getModel('brandmodel/requestbrand');

            $request_brand_model->setData($data);
            $request_brand_model->setCreatedTime(now());
            $request_brand_model->setUpdateTime(now());
            try {
                $request_brand_model->save();
                $result['status'] = '1';
            } catch (Exception $e) {
                $result['status'] = '0';
                $result['message'] = $e->getMessage();
            }

        } else {
            $result['status'] = '0';
            $result['message'] = 'Dữ liệu không hợp lệ.Vui lòng kiểm tra.';
        }

        $this->getResponse()->setHeader('Content-type', 'application/json');
        return $this->getResponse()->setBody(json_encode($result));

    }

    protected function checkRequestBrandData($data)
    {

        $user_id = $data['user_id'];
        $email = $data['email'];
        $phone = $data['phone'];
        $requirement = $data['requirement'];


        if (!$user_id) {

            if (!$email) {
                return false;
            }

            if (!$phone) {
                return false;
            }

        }

        if (!$requirement) {
            return false;
        }


        return true;

    }

}