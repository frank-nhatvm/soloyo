<?php
class Soloyo_Casedesign_IndexController extends Mage_Core_Controller_Front_Action
{
    /**
     * index action
     */
    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }


    public function customer_uploadAction(){

        $result_data = array();
        if (isset($_FILES['custom_image_user']['name']) && $_FILES['custom_image_user']['name'] != '') {
            try {
                /* Starting upload */
                $uploader = new Varien_File_Uploader('custom_image_user');

                // Any extention would work
                $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
                $uploader->setAllowRenameFiles(false);


                $uploader->setFilesDispersion(false);

                // We set media as the upload dir
                $base_folder =  DS . 'casedesign' . DS.'customer'.DS.Mage::helper('casedesign')->getSessionId().DS;
                $path = Mage::getBaseDir('media') .$base_folder;
                $result = $uploader->save($path, time().$_FILES['custom_image_user']['name']);
                $result_data['base_url'] = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . $base_folder;
                $result_data['url_file'] = $result['file'];
                $result_data['success'] = '1';
            } catch (Exception $e) {
                $result_data['success'] = '0';
                $result_data['message'] = $e->getMessage();
            }
        }
        else{
            $result_data['success'] = '0';
            $result_data['message'] = 'Please select a file';
        }

        $this->getResponse()->setHeader('Content-type', 'application/json');
        return $this->getResponse()->setBody(json_encode($result_data));
    }


    public function save_designAction(){
        $result_data = array();
        if ($data = $this->getRequest()->getPost()) {

            if(isset($data['product_id']) && $data['product_id']){
                $product_id = $data['product_id'];
                $_SESSION['casedesign']['product_id'] = $product_id;
            }else{
                $result_data['status'] = '0';
            }

            if(isset($data['design_json']) && $data['design_json']){
                $_SESSION['casedesign']['design_json_'.$product_id] = Mage::helper('casedesign')->saveCasedesignCustomerJson($data['design_json']);
                $result_data['status'] = '1';
            }else{
                $result_data['status'] = '0';
            }

            if($data_image = $data['image']){
                $image_name = Mage::helper('casedesign')->saveCasedesignCustomerImage($data_image);
                $result_data['image_name'] =$image_name;
                if($image_name){
                    $_SESSION['casedesign']['image_'.$product_id] = $image_name;
                    $_SESSION['casedesign']['thumb_'.$product_id] = $image_name;
                }
                $result_data['thumb_image'] = $_SESSION['casedesign']['thumb_'.$product_id] ;

            }
        }
        $this->getResponse()->setHeader('Content-type', 'application/json');
        return $this->getResponse()->setBody(json_encode($result_data));

    }




}