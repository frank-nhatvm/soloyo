<?php

include_once(Mage::getBaseDir('lib').DS.'soloyo'.DS.'SimpleImage.php');

class Soloyo_Casedesign_Helper_Data extends Mage_Core_Helper_Abstract
{

    public function getCaseDesignProduct(){
        $collection = Mage::getModel('catalog/product')->getCollection()
            ->addFieldToFilter('casedesign','1')
            ->addAttributeToSelect('sku')
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('attribute_set_id')
            ->addAttributeToSelect('type_id');

        if (Mage::helper('catalog')->isModuleEnabled('Mage_CatalogInventory')) {
            $collection->joinField('qty',
                'cataloginventory/stock_item',
                'qty',
                'product_id=entity_id',
                '{{table}}.stock_id=1',
                'left');
        }

        $collection
            ->addFieldToFilter('visibility', Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)
            ->addAttributeToFilter('type_id', array('in' => array('configurable', 'simple', 'virtual', 'bundle', 'downloadable')))
            ->addAttributeToFilter('status', array('eq' => Mage_Catalog_Model_Product_Status::STATUS_ENABLED));
        return $collection;
    }


    public function hasDesignForProductId($product_id){
        $collection = Mage::getModel('casedesign/casedesign')->getCollection()
            ->addFieldToFilter('product_id',$product_id)->addFieldToFilter('status','1');

        if($collection && count($collection) > 0){
            return 1;
        }

        return 0;
    }

    public function saveCasedesignCustomerImage($data_image){
        try {
            $temp = explode(',', $data_image);
            $buffer = base64_decode($temp[1]);

            $path = $this->getPathCasedesignCustomer();
            $id_folder = $this->getSessionId();
            $image_name = $id_folder.DS.time().'.png';

            $folder_image = $path.$id_folder.DS;
            if (!file_exists($folder_image) ) {
                mkdir($folder_image, 0777, true);
            }

            $full_path_image = $path.$image_name;
            $fp = fopen($full_path_image, 'w');
            if (!$fp) {

                return null;
            }

            flock($fp, LOCK_EX);

            fwrite($fp, $buffer);

            flock($fp, LOCK_UN);

            fclose($fp);

            return $image_name;
        }
        catch (Exception $e){

            echo $e->getMessage();
            die();
        }

        return null;
    }



    public function createThumbImage($full_image){
        $path = $this->getPathCasedesignCustomer();
        $full_path_image = $path.$full_image;
        $id_folder = $this->getSessionId();
        $thumb_file = $id_folder.DS.'thumb.png';
        $full_path_thumb = $path.$thumb_file;

        $image = new SimpleImage();
        $image->load($full_path_image);


        $thumb_width = 300;
        $thumb_height = 300;
        $thumb_quality = 1;
        $image->resize($thumb_width, $thumb_height, 1);
//            $image->set_quality($thumb_quality);
        $image->save($full_path_thumb);
        return $thumb_file;
        /**/



    }

    public function saveCasedesignCustomerJson($data_json){
        try{
            $path = $this->getPathCasedesignCustomer();
            $id_folder = $this->getSessionId();
            $json_file = $id_folder.DS.'design.json';

            $folder_path = $path.$id_folder.DS;
            if (!file_exists($folder_path) ) {
                mkdir($folder_path, 0777, true);
            }


            $full_path_json = $path.$json_file;
            file_put_contents($full_path_json, $data_json);

            return $json_file;
        }catch (Exception $e){

        }
        return null;

    }

    public function getSessionId(){
        return session_id();
    }

    protected function wp_get_image_editor( $filename) {


        try {
            $type = exif_imagetype($filename);
//        if ($type == IMAGETYPE_PNG) {
            $image = @imagecreatefrompng($filename);
            $x = ImageSX($image);
            $y = ImageSY($image);


//        } elseif ($type == IMAGETYPE_JPEG) {
//            $image = imagecreatefromjpeg($full_name);
//        }
        }catch (Exception $e){
            echo ' exception '.$e->getMessage();
        }
        return $image;
    }

    public function getPathCasedesignCustomerImage($image_name){
        $path = $this->getPathCasedesignCustomer();
        return $path.$image_name;
    }

    public function getPathCasedesignCustomer(){
        $path = Mage::getBaseDir('media') . DS . 'casedesign' . DS.'customer'.DS;
        return $path;
    }


    public function getUrlPathCasedesignCustomer(){
        return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA). DS . 'casedesign' . DS.'customer'.DS;
    }

    /*
     * path for saving image in admin
     */
    public function getPathCasedesignTemplate($product_id){
        return   Mage::getBaseDir('media') . DS . 'casedesign' . DS.'template'.DS.$product_id.DS;
    }




    /**
     * path for show image in admin
     */
    public function getUrlPathCasedesignTemplate($product_id){
        return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . DS . 'casedesign' . DS.'template'.DS.$product_id.DS;
    }


}