<?php

class Soloyo_Productupload_IndexController extends Mage_Core_Controller_Front_Action
{
    public function downloadAction(){
        $printer_id = $this->getRequest()->getParam('printer_id');
        $is_customer_design = $this->getRequest()->getParam('is_customer_design');
        $image_path = $this->getRequest()->getParam('image_path');

        if($printer_id  && $image_path){
            $printer = Mage::getModel('printer/printer')->load($printer_id);
            if($printer && $printer->getId() && $printer->getStatus() == 1){

                if($is_customer_design){
                    $root_image_path = Mage::helper('casedesign')->getPathCasedesignCustomer();
                }else{
                    $root_image_path = Mage::helper('productupload')->getDirImageProductUpload();
                }

                $path_image_download = $root_image_path.$image_path;

                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="'.basename($path_image_download).'"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($path_image_download));
                readfile($path_image_download);
                exit;

            }else{
                echo 'Bạn không thể download file này.Tài khoản của bạn đã bị khoá. Liên hệ admin: nhat.thtb@gmail.com';
                exit;
            }


        }else{
            echo 'Bạn không thể download file này. Liên hệ admin: nhat.thtb@gmail.com';
            exit;
        }

    }

    public function download_demoAction(){
        //https://soloyo.vn/media//productupload/product/iphone6_print.pdf
        $path_image_download =   Mage::helper('productupload')->getDirImageProductUpload().'print_sample.pdf';

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($path_image_download).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($path_image_download));
        readfile($path_image_download);
        exit;
    }

    public  function requestproductAction(){
        $this->loadLayout();
        $this->renderLayout();
    }

    public function requestproduct_postAction(){
        $data = $this->getRequest()->getPost();
        $result = array();

        if ($data && $this->checkRequestProductData($data)) {
            $request_brand_model = Mage::getModel('productupload/requestproduct');

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

    protected function checkRequestProductData($data)
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