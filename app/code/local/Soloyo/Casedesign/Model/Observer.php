<?php

class Soloyo_Casedesign_Model_Observer
{
    public function checkoutCartProductAddAfter($observer)
    {
        $item = $observer->getEvent()->getQuoteItem();
        $pid = $_SESSION['casedesign']['product_id'];
        $design_json_file = $_SESSION['casedesign']['design_json_' . $pid];
        $image_file = $_SESSION['casedesign']['image_' . $pid];
        $thumb_file = $_SESSION['casedesign']['thumb_' . $pid];

        $item->setData('casedesign_json', $design_json_file);
        $item->setData('casedesign_pid', $pid);
        $item->setData('casedesign_image', $image_file);
        $item->setData('casedesign_thumb', $thumb_file);

        unset($_SESSION['casedesign']);
        session_regenerate_id();
        return $this;
    }


    public function salesOrderSaveAfter($observer)
    {
        $order = $observer['order'];

        if ($order->getState() == Mage_Sales_Model_Order::STATE_PROCESSING) {
            try {
                $this->prepareForPrintCase($order);
            } catch (Exception $e) {
                echo ' observer ' . $e->getMessage();
                die('xxx');
            }

        } else if ($order->getState() == Mage_Sales_Model_Order::STATE_COMPLETE) {
            $this->updateBalanceDesigner($order);
        }
    }

    protected function prepareForPrintCase($order)
    {
        $order_id = $order->getId();
        $real_order = Mage::getModel('sales/order')->load($order_id);

        $caseorder_model = Mage::getModel('casedesign/caseorder');
        $caseorder_model->setOrderId($order_id);
        $caseorder_model->setCreatedTime(now());
        $caseorder_model->setUpdatedTime(now());
        $caseorder_model->save();
        $caseorder_id = $caseorder_model->getId();

        $items = $real_order->getItemsCollection();

        $array = array();
        foreach ($items as $item) {

            $casedesign_thumb = $item->getCasedesignThumb();
            if ($casedesign_thumb && $item->getCasedesignPid()) {
                // design by customer
                $array[] = $casedesign_thumb;
                $this->saveCaseOrderItemCustom($item, $caseorder_id);
            } else {
                // design by admin or designer
                $this->saveCaseOrderItem($item, $caseorder_id);
            }

        }
        $list_thumb = implode(', ', $array);
        $caseorder_model->setThumbImage($list_thumb);
        $caseorder_model->save();
    }

    protected function saveCaseOrderItemCustom($order_item, $caseorder_id)
    {
        $casedesign_json = $order_item->getCasedesignJson();
        $casedesign_image = $order_item->getCasedesignImage();
        $casedesign_thumb = $order_item->getCasedesignThumb();
        $product_id = $order_item->getProductId();

        $qty_ordered = $order_item->getQtyOrdered();
        $options = $order_item->getProductOptions();
        $customOptions = $options['options'];
        $options = null;
        if ($customOptions && is_array($customOptions) && count($customOptions)) {
            foreach ($customOptions as $option) {
                $label = $option['label'];
                $value = $option['value'];
                $options = $options . $label . ' : ' . $value . ', ';
            }
        }


        $caseorderitem_model = Mage::getModel('casedesign/caseorderitem');
        $caseorderitem_model->setCaseorderId($caseorder_id);

        $caseorderitem_model->setQtyOrdered($qty_ordered);
        $caseorderitem_model->setOptions($options);

        $caseorderitem_model->setProductId($product_id);
        $caseorderitem_model->setThumbImage($casedesign_thumb);
        $caseorderitem_model->setDesignImage($casedesign_image);
        $caseorderitem_model->setContentDesign($casedesign_json);
        $caseorderitem_model->setIsCustomerDesign(1);
        $caseorderitem_model->setCreatedTime(now());
        $caseorderitem_model->setUpdateTime(now());
        $caseorderitem_model->save();
    }

    protected function saveCaseOrderItem($order_item, $caseorder_id)
    {
        $product_id = $order_item->getProductId();
        $design_image = $this->getImagePrint($product_id);
        $thumb_image = $this->getThumbImage($product_id);

        $qty_ordered = $order_item->getQtyOrdered();
        $options = $order_item->getProductOptions();
        $customOptions = $options['options'];
        $options = null;
        if ($customOptions && is_array($customOptions) && count($customOptions)) {
            foreach ($customOptions as $option) {
                $label = $option['label'];
                $value = $option['value'];
                $options = $options . $label . ' : ' . $value . ', ';
            }
        }

        $caseorderitem_model = Mage::getModel('casedesign/caseorderitem');
        $caseorderitem_model->setProductId($product_id);

        $caseorderitem_model->setQtyOrdered($qty_ordered);
        $caseorderitem_model->setOptions($options);

        $caseorderitem_model->setThumbImage($thumb_image);
        $caseorderitem_model->setDesignImage($design_image);
        $caseorderitem_model->setCaseorderId($caseorder_id);
        $caseorderitem_model->setCreatedTime(now());
        $caseorderitem_model->setUpdateTime(now());
        $caseorderitem_model->save();
    }

    protected function getImagePrint($product_id)
    {
        $image_print = Mage::getModel('productupload/imageprint')->getCollection()
            ->addFieldToFilter('product_id', $product_id)->getFirstItem();
        return $image_print->getImageProduct();
    }

    protected function getThumbImage($product_id)
    {
        $product = Mage::getModel('catalog/product')->load($product_id);
        return Mage::helper('catalog/image')->init($product, 'thumbnail')->resize(150, 220);
    }

    protected function updateBalanceDesigner($order)
    {

        $order_id = $order->getId();
        if (Mage::getStoreConfig('campaign/general/enable'))
        {
            $this->updateStatusPlayer($order_id);
        }
        $real_order = Mage::getModel('sales/order')->load($order_id);
        $items = $real_order->getItemsCollection();

        $list_admin_design_ids = $this->getAdminDesignId();
        foreach ($items as $item) {

            if ($item->getCasedesignThumb() && $item->getCasedesignPid()) {
                continue;
            }

            $product_id = $item->getProductId();
            $qty_ordered = $item->getQtyOrdered();
            $this->increaseDesignerBalance($qty_ordered,$product_id, $list_admin_design_ids);
        }
    }


    protected function updateStatusPlayer($order_id){
        $campaign_id = '3';
        $collection = Mage::getModel('campaign/affplayer')->getCollection()->addFieldToFilter('face_id', $order_id)->addFieldToFilter('campaign_id',$campaign_id);
        if ($collection && count($collection)) {
            $first_item = $collection->getFirstItem();
            $id = $first_item->getId();
            $model = Mage::getModel('campaign/affplayer')->load($id);
            $model->setStatus(2);
            $model->save();
        }

        $campaign = Mage::getModel('campaign/campaign')->load($campaign_id);
        if($campaign && $campaign->getId()){
            $num_player = $campaign->getNumPlayer();
            $campaign->setNumPlayer($num_player+1);
            $campaign->save();
        }
    }



    protected function increaseDesignerBalance($qty_ordered,$product_id, $list_admin_design_ids)
    {

        $product = Mage::getModel('catalog/product')->load($product_id);

        $designer_id = $product->getDesignerId();
        if ($designer_id && !$this->isAdminDesigner($designer_id, $list_admin_design_ids)) {
            $designer = Mage::getModel('productupload/designer')->load($designer_id);
            $designer_email = $designer->getEmail();
            $current_balance = $designer->getBalance();
            $price = $qty_ordered * 30000;
            $designer->setBalance($current_balance + $price);
            $current_total_balance = $designer->getTotalBalance();
            $designer->setTotalBalance($current_total_balance+$price);
            $designer->setUpdateTime(now());
            $designer->save();
        }


        $collection = Mage::getModel('productupload/productupload')->getCollection()
            ->addFieldToFilter('product_id', $product_id)->addFieldToFilter('is_admin', 0)
            ->addFieldToFilter('sale_type', '2')->addFieldToFilter('designer_id', $designer_id);

        $first_item = $collection->getFirstItem();
        if ($first_item && $first_item->getDesignerId()) {

            $product_upload = Mage::getModel('productupload/productupload')->load($first_item->getId());
            $name = $product_upload->getName();
            $old_qty_sale = $product_upload->getQtySale();
            $new_qty_sale = $old_qty_sale + $qty_ordered;
            $product_upload->setQtySale($new_qty_sale);
            $product_upload->save();

        }

        if ($designer_id && $name) {
            $user_id = $designer->getUserId();
            $user = Mage::getModel('customer/customer')->load($user_id);
            $designer_name = $user->getFirstname();

            $this->notifyToDesigner($designer_name,$designer_email, $name);
        }

    }

    public function notifyToDesigner($designer_name,$designer_email, $product_name)
    {
        try {
            $emailTemplate = Mage::getModel('core/email_template')->load('2');

            $email_data = array('designer_name' => $designer_name,'product_name'=>$product_name);

            $vars =array('custom_designer_data' => $email_data);
            $emailTemplate->getProcessedTemplate($vars);

            $emailTemplate->setSenderName('Soloyo');
            $store_id = Mage::app()->getStore()->getId();
            $sender_email = Mage::getStoreConfig('trans_email/ident_general/email', $store_id);

            $emailTemplate->setSenderEmail($sender_email);
            $sender_name = Mage::getStoreConfig('trans_email/ident_general/name', $store_id);
            $emailTemplate->setSenderName($sender_name);

            if ($emailTemplate->send($designer_email, $designer_name, $vars)) {

                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }

    protected function isAdminDesigner($designer_id, $list_admin_design_ids)
    {
        if (!$list_admin_design_ids) {
            return false;
        }

        if (!count($list_admin_design_ids)) {
            return false;
        }

        foreach ($list_admin_design_ids as $admin_designer_id) {
            if ($admin_designer_id == $designer_id) {
                return true;
            }
        }

        return false;

    }

    protected function getAdminDesignId()
    {
        $ids = Mage::getStoreConfig('productupload/general/admin_design_id');
        if (!$ids) {
            return null;
        } else {
            $array = explode(',', $ids);
            if ($array && is_array($array)) {
                return $array;
            } else {
                return null;
            }
        }
    }

    protected function getMinimumPrice()
    {
        return Mage::getStoreConfig('productupload/general/minimum_price');
    }

}