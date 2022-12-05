<?php

class Soloyo_Productupload_Helper_Data extends Mage_Core_Helper_Abstract
{

    const DIR_IMAGE_PRODUCT_UPLOAD = 'productupload'.DS.'product'.DS;
    const  DIR_IMAGE_CATEGORY_UPLOAD = 'productupload'.DS.'category'.DS;
    const DIR_DESIGNER_TRANSACTION = 'designer'.DS.'transaction'.DS;

    const DIR_IMAGE_MOCKUP = 'productupload'.DS.'mockup'.DS;

    public function getDirImageMockup(){
        $dir = Mage::getBaseDir('media') . DS.self::DIR_IMAGE_MOCKUP;
        if(!file_exists($dir)){
            mkdir($dir, 0777, true);
        }

        return $dir;
    }

    public function getUrlImageMockup(){
        return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA). DS.self::DIR_IMAGE_MOCKUP;
    }


    public function getDirImageDesingerTransaction(){
        $dir = Mage::getBaseDir('media') . DS.self::DIR_DESIGNER_TRANSACTION;
        if(!file_exists($dir)){
            mkdir($dir, 0777, true);
        }

        return $dir;
    }

    public function getUrlImageDesignerTransaction(){
        return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA). DS.self::DIR_DESIGNER_TRANSACTION;
    }

    public function getDirImageProductUpload(){
        $dir = Mage::getBaseDir('media') . DS .self::DIR_IMAGE_PRODUCT_UPLOAD;

        if(!file_exists($dir)){
            mkdir($dir, 0777, true);
        }

        return $dir;
    }

    public function getUrlImageProductUpload(){
        return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA). DS.self::DIR_IMAGE_PRODUCT_UPLOAD;
    }

    public function getDirImageCategoryUpload(){
        $dir = Mage::getBaseDir('media') . DS .self::DIR_IMAGE_CATEGORY_UPLOAD;
        if(!file_exists($dir)){
            mkdir($dir, 0777, true);
        }
        return $dir;
    }

    public function getUrlImageCategoryUpload(){
        return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA). DS.self::DIR_IMAGE_CATEGORY_UPLOAD;
    }


    public function isAdminDesign($design_id){
        $admin_design_ids = Mage::getStoreConfig('productupload/general/admin_design_id');
        $list_id = explode(',',$admin_design_ids);
        if($list_id && is_array($list_id)){
            return in_array($design_id,$list_id);
        }

        return false;
    }

    public function getAllCategory(){

        $root_category = Mage::app()->getStore()->getRootCategoryId();
        $collection = Mage::getModel('catalog/category')->load($root_category)->getCollection()->addAttributeToSelect('name')->addAttributeToFilter('is_active', 1);



        $result = array();
        foreach ($collection as $item){
            $item_result = array();
            $item_result['value'] = $item->getId();
            $item_result['label'] = $item->getName();
            $result[] = $item_result;
        }

        return $result;

    }


    public function isLogged(){
        return Mage::getSingleton('customer/session')->isLoggedIn();
    }

    public function getCustomerId(){
        return Mage::getSingleton('customer/session')->getCustomer()->getId();
    }

    public function getCustomter(){
        return Mage::getSingleton('customer/session')->getCustomer();
    }

    public function isDesigner(){
        if(!$this->isLogged()){
            return false;
        }

        $customer_id = $this->getCustomerId();
        $collection = Mage::getModel('productupload/designer')->getCollection()
            ->addFieldToFilter('user_id',$customer_id);

        if($collection && count($collection)){
            return true;
        }

        return false;

    }

    public function getDesigner(){
        if(!$this->isLogged()){
            return false;
        }

        $customer_id = $this->getCustomerId();
        $designer = Mage::getModel('productupload/designer')->getCollection()
            ->addFieldToFilter('user_id',$customer_id)->getFirstItem();
        return $designer;

    }

    public function saveImageFile($key_file, $is_product = true)
    {
        if (isset($_FILES[$key_file]['name']) && $_FILES[$key_file]['name'] != '') {
            try {
                $uploader = new Varien_File_Uploader($key_file);
                $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png','pdf'));
                $uploader->setAllowRenameFiles(false);
                $uploader->setFilesDispersion(false);

                $path = $this->getDirImageProductUpload();
                if (!$is_product) {
                    // save image for category
                    $path = $this->getDirImageCategoryUpload();
                }

                $file_name = time() . $_FILES[$key_file]['name'];
                $result = $uploader->save($path, $file_name);
                return $result['file'];
            } catch (Exception $e) {
                return $_FILES[$key_file]['name'];
            }
        }
        return '';
    }

}