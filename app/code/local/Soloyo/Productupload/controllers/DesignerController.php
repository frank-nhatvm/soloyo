<?php

class Soloyo_Productupload_DesignerController extends Mage_Core_Controller_Front_Action
{

    public function introAction()
    {
        if (Mage::helper('productupload')->isDesigner()) {
            $this->_redirect('*/*/index');
            return;
        }
        $this->loadLayout();
        $this->renderLayout();
    }

    public function guideAction()
    {
        if (!$this->_getSession()->isLoggedIn()){
            $this->_redirect('customer/account/login');
            $back_url = Mage::getUrl('*/*/guide', array('_secure' => true));
            Mage::getSingleton('customer/session')->setData('after_auth_url',$back_url);
            return;
        }
        if (!Mage::helper('productupload')->isDesigner()) {
            $this->_redirect('*/*/register');
            return;
        }
        $this->loadLayout();
        $this->renderLayout();
    }

    public function indexAction()
    {
        if (!$this->_getSession()->isLoggedIn()){
            $back_url = Mage::getUrl('*/*/index', array('_secure' => true));
            Mage::getSingleton('customer/session')->setData('after_auth_url',$back_url);
            $this->_redirect('customer/account/login');
            return;
        }
        if (!Mage::helper('productupload')->isDesigner()) {
            $this->_redirect('*/*/register');
            return;
        }
        $this->loadLayout();
        $this->renderLayout();
    }

    public function transactionAction()
    {
        if (!$this->_getSession()->isLoggedIn()){
            $back_url = Mage::getUrl('*/*/transaction', array('_secure' => true));
            Mage::getSingleton('customer/session')->setData('after_auth_url',$back_url);
            $this->_redirect('customer/account/login');
            return;
        }
        if (!Mage::helper('productupload')->isDesigner()) {
            $this->_redirect('*/*/register');
            return;
        }
        $this->loadLayout();
        $this->renderLayout();
    }

    public function designAction()
    {
        if (!$this->_getSession()->isLoggedIn()){
            $back_url = Mage::getUrl('*/*/design', array('_secure' => true));
            Mage::getSingleton('customer/session')->setData('after_auth_url',$back_url);
            $this->_redirect('customer/account/login');
            return;
        }
        if (!Mage::helper('productupload')->isDesigner()) {
            $this->_redirect('*/*/register');
            return;
        }
        $this->loadLayout();
        $this->renderLayout();
    }

    public function linkbuilderAction(){
        if (!$this->_getSession()->isLoggedIn()){
            $this->_redirect('customer/account/login');
            return;
        }

        if (!Mage::helper('productupload')->isDesigner()) {
            $this->_redirect('*/*/register');
            return;
        }

        $this->loadLayout();
        $this->renderLayout();
    }

    public function sizeAction(){
        if (!$this->_getSession()->isLoggedIn()){
            $back_url = Mage::getUrl('*/*/size', array('_secure' => true));
            Mage::getSingleton('customer/session')->setData('after_auth_url',$back_url);
            $this->_redirect('customer/account/login');
            return;
        }
        if (!Mage::helper('productupload')->isDesigner()) {
            $this->_redirect('*/*/register');
            return;
        }
        $this->loadLayout();
        $this->renderLayout();
    }

    public function registerAction()
    {
        if ($this->_getSession()->isLoggedIn() && Mage::helper('productupload')->isDesigner()) {
            $this->_redirect('*/*/index');
            return;
        }
        $this->loadLayout();
        $this->renderLayout();
    }

    protected function _getSession()
    {
        return Mage::getSingleton('customer/session');
    }

    public function register_postAction()
    {

        $session = $this->_getSession();

        if ($data = $this->getRequest()->getPost()) {

            $customer = $data['customer'];

            if (isset($customer['customer_id']) && $customer['customer_id']) {
                $customer_id = $customer['customer_id'];
            } else {
                try {
                    $customer_id = $this->register_new_user($customer);
                } catch (Exception $e) {
                    $session->addError($e->getMessage());
                    $this->_redirect('*/*/register');
                    echo $e->getMessage();
                    return;
                }

            }

            if($this->checkExistDesigner($customer_id))
            {
                $session->addError('T??i kho???n n??y ???? ????ng k?? ch????ng tr??nh Designer.N???u b???n ch??a ???????c ph?? duy???t vui l??ng ch??? ph???n h???i t??? Admin');
                $this->_redirect('*/*/register');
                return;
            }else{

                $designer = $data['designer'];
                $designer['email'] =  $customer['email'];
                $designer['user_id'] = $customer_id;

                $designer_model = Mage::getModel('productupload/designer');
                $designer_model->setData($designer);
                $designer_model->save();

                $session->addSuccess('B???n ???? ????ng k?? th??nh c??ng ch????ng tr??nh Designer');
                $this->_redirect('*/*/index');

                return;
            }

        }

        $session->addError($this->__('Cannot register. Please try again'));
        $this->_redirect('*/*/register');
    }

    protected function checkExistDesigner($customer_id){
        $collection = Mage::getModel('productupload/designer')->getCollection()
            ->addFieldToFilter('user_id',$customer_id);
        if($collection && count($collection)){
            return true;
        }
        return false;
    }

    public function update_infoAction()
    {
        if ($data = $this->getRequest()->getPost()) {

            $designer = $data['designer'];
            if (isset($designer['balance'])) {
                unset($designer['balance']);
            }

            $designer_id = $designer['designer_id'];

            $model = Mage::getModel('productupload/designer')->load($designer_id);
            $model->setData($designer);
            $model->save();

            $this->_redirect('*/*/index');
            return;

        }
    }

    public function newdesignAction()
    {
        if (!Mage::helper('productupload')->isDesigner()) {
            $this->_redirect('customer/account/login');
            return;
        }
        $this->loadLayout();
        $this->renderLayout();
    }

    public function editdesignAction()
    {
        if (!Mage::helper('productupload')->isDesigner()) {
            $this->_redirect('customer/account/login');
            return;
        }
        $this->loadLayout();
        $this->renderLayout();
    }

    public function updatedesignAction(){
        $session = $this->_getSession();
        if ($data = $this->getRequest()->getPost()) {
            $productupload_id = $data['productupload_id'];
            if($productupload_id){
                $productupload = Mage::getModel('productupload/productupload')->load($productupload_id);
                if($productupload && $productupload->getId()){

                    if($productupload->getStatus() != 1){

                        if (isset($_FILES['image_print']['name']) && $_FILES['image_print']['name']) {
                            $image_print = Mage::helper('productupload')->saveImageFile('image_print', true);
                            $productupload->setImagePrint($image_print);
                        }

                        if (isset($_FILES['image_product']['name']) && $_FILES['image_product']['name']) {
                            $image_product = Mage::helper('productupload')->saveImageFile('image_product', true);
                            $productupload->setImageProduct($image_product);
                        }


                        if(isset($data['description']) && $data['description']){
                            $productupload->setDescription($data['description']);
                        }

                        $productupload->setStatus(0);
                        $productupload->save();
                        $session->addSuccess($this->__("Y??u c???u c???p nh???t thi???t k??? ???? ???????c g???i t???i Soloyo. Vui l??ng ?????i ki???m duy???t c???a admin"));
                        $this->_redirect('*/*/design');
                        return;

                    }else{
                        $session->addError($this->__('B???n kh??ng th??? s???a thi???t k??? n??y.'));
                    }

                }else{
                    $session->addError($this->__('Ch??a l??u ???????c thi???t k???. Vui l??ng th??? l???i'));
                }
            }
            else{
                $session->addError($this->__('Ch??a l??u ???????c thi???t k???. Vui l??ng th??? l???i'));
            }
        }else{
            $session->addError($this->__('Ch??a l??u ???????c thi???t k???. Vui l??ng th??? l???i'));
        }

        $this->_redirect('*/*/design');
        return;

    }

    public function creatNewDesignAction()
    {

        $session = $this->_getSession();

        if ($data = $this->getRequest()->getPost()) {

            if (isset($data['cate_design_type']) && $data['cate_design_type'] == 2) {
                $data['cate_name'] = $data['new_cate_design'];
                unset($data['new_cate_design']);
            }

            if (isset($_FILES['image_print']['name']) && $_FILES['image_print']['name']) {
                echo ' print '.$_FILES['image_print']['name'];
                $data['image_print'] = Mage::helper('productupload')->saveImageFile('image_print', true);
            }

            if (isset($_FILES['image_product']['name']) && $_FILES['image_product']['name']) {
                $data['image_product'] = Mage::helper('productupload')->saveImageFile('image_product', true);
            }

            try {
                $productupload = Mage::getModel('productupload/productupload');
                $productupload->setData($data);
                $productupload->save();
                $session->addSuccess($this->__("Thi???t k??? cu??? b???n ???? ???????c g???i t???i Soloyo. Vui l??ng ?????i ki???m duy???t c???a admin"));
                $this->_redirect('*/*/design');
                return;
            } catch (Exception $e) {
                Mage::log('Create new design fail '.$e->getMessage(), null, 'newdesign.log');
                $session->addError($this->__('Ch??a l??u ???????c thi???t k???. Vui l??ng th??? l???i'));
                $this->_redirect('*/*/design');
                return;
            }


        }

        $session->addError($this->__('Ch??a l??u ???????c thi???t k???. Vui l??ng th??? l???i'));
        $this->_redirect('*/*/design');
        return;
    }

    protected function register_new_user($customer_info)
    {

        $check_customer = $this->getCustomerByEmail($customer_info['email']);
        if ($check_customer->getId()) {

            Mage::getSingleton('customer/session')->setCustomerAsLoggedIn($check_customer);
            return $check_customer->getId();
        } else {

            $customer = Mage::getModel('customer/customer')
                ->setWebsiteId(Mage::app()->getWebsite()->getId());
            $customer->setData($customer_info);
            $customer->save();

            Mage::getSingleton('customer/session')->setCustomerAsLoggedIn($customer);
            return $customer->getId();
        }
    }

    public function getCustomerByEmail($email)
    {
        return Mage::getModel('customer/customer')
            ->getCollection()
            ->addFieldToFilter('website_id', Mage::app()->getWebsite()->getId())
            ->addFieldToFilter('email', $email)
            ->getFirstItem();
    }

    public function drawRequestAction()
    {
        $session = Mage::getSingleton('core/session');
        $message = 'C?? l???i x???y ra trong qu?? tr??nh g???i y??u c???u r??t ti???n. Vui l??ng th??? l???i';
        if ($data = $this->getRequest()->getPost()) {
            $designer_id = $data['designer_id'];
            $amount = $data['amount'];
            if ($designer_id && $amount) {

                $designer = Mage::getModel('productupload/designer')->load($designer_id);
                if ($designer->getId()) {

                    $balance = $designer->getBalance();
                    $new_balance = $balance - $amount;
                    $message = $this->validateAmount($amount, $balance);
                    if (!$message) {
                        try {
                            $designer->setBalance($new_balance);
                            $designer->save();

                            $model = Mage::getModel('productupload/designertransaction');
                            $model->setData($data);
                            $model->setCreatedTime(now());
                            $model->setUpdateTime(now());
                            $model->save();
                            $session->addSuccess('Y??u c???u r??t ti???n c???a b???n ???? ???????c g???i. Vui l??ng ?????i Soloyo chuy???n kho???n');
                            $this->_redirect('*/*/transaction');
                            return;
                        } catch (Exception $e) {

                        }
                    }
                } else {
                    $message = 'Kh??ng x??c ?????nh ???????c Designer. Vui l??ng ????ng nh???p l???i.';
                }
            } else {
                $message = 'Kh??ng x??c ?????nh ???????c Designer. Vui l??ng ????ng nh???p l???i.';
            }
        }

        $session->addError($message);
        $this->_redirect('*/*/transaction');
        return;
    }

    protected function validateAmount($amount, $balance)
    {

        if ($amount < 50000) {
            return 'S??? ti???n mu???n r??t ph???i l???n h??n 50000';
        }

        $x = $amount % 50000;
        if ($x != 0) {
            return 'S??? ti???n mu???n r??t ph???i l?? b???i c???a 50000';
        }

        $new_balance = $balance - $amount;

        if ($new_balance < 0) {
            return 'S??? ti???n mu???n r??t l???n h??n s??? d?? c???a b???n. Vui l??ng th??? l???i v???i s??? ti???n th???p h??n';
        }


        return null;
    }


    public function getSizeAction(){
        $brand_id = $this->getRequest()->getParam('brand_id');
        $model_id = $this->getRequest()->getParam('model_id');
        $result = array();
        if($brand_id && $model_id){
            $categoryId = '3';

            $product =  Mage::getModel('catalog/category')
                ->load($categoryId)
                ->getProductCollection()->addFieldToFilter('brand',$brand_id)->addFieldToFilter('brand_model',$model_id)
                ->getFirstItem();
            ;
            if($product && $product->getId()){

                $design_product = Mage::getModel('casedesign/casedesign')->getCollection()
                    ->addFieldToFilter('product_id',$product->getId())->addFieldToFilter('status','1')->getFirstItem();
                if($design_product && $design_product->getId()){

                    $design_area_width = $design_product->getDesignAreaWidth();
                    $design_area_height = $design_product->getDesignAreaHeight();

                    $result['status'] = '1';
                    $result['width'] = $design_area_width;
                    $result['height'] = $design_area_height;

                    $this->getResponse()->setHeader('Content-type', 'application/json');
                    return $this->getResponse()->setBody(json_encode($result));

                }
            }
        }
        $result['status'] = '0';
        $result['message'] = 'Ch??a c?? k??ch th?????c cho lo???i ??i???n tho???i b???n y??u c???u. Vui l??ng li??n h??? admin: nhat.thtb@gmailcom';

        $this->getResponse()->setHeader('Content-type', 'application/json');
        return $this->getResponse()->setBody(json_encode($result));

    }


    public function mockupAction()
    {
        if (!$this->_getSession()->isLoggedIn()) {
            $back_url = Mage::getUrl('*/*/mockup', array('_secure' => true));
            Mage::getSingleton('customer/session')->setData('after_auth_url', $back_url);
            $this->_redirect('customer/account/login');
            return;
        }
        if (!Mage::helper('productupload')->isDesigner()) {
            $this->_redirect('*/*/register');
            return;
        }
        $this->loadLayout();
        $this->renderLayout();
    }

    public function getTemplateAction()
    {
        $brand_id = $this->getRequest()->getParam('brand_id');
        $model_id = $this->getRequest()->getParam('model_id');
        $result = array();

        if ($brand_id && $model_id) {

            $collection = Mage::getModel('productupload/mockup')->getCollection()->addFieldToFilter('brand_id', $brand_id)
                ->addFieldToFilter('model_id', $model_id)->addFieldToFilter('status', '1');
            if ($collection && count($collection)) {

                foreach ($collection as $mockup) {
                    $item = $mockup->toArray();


                    $designer_id = $mockup['designer_id'];
                    if ($designer_id) {
                        $designer = Mage::getModel('productupload/designer')->load($designer_id);
                        $user_id = $designer->getUserId();
                        $customer = Mage::getModel('customer/customer')->load($user_id);
                        $item['upload_by'] = $customer->getFirstname();
                    } else {
                        $item['upload_by'] = 'Admin';
                    }
                    $result['items'][] = $item;
                }
                $result['status'] = '1';
                $this->getResponse()->setHeader('Content-type', 'application/json');
                return $this->getResponse()->setBody(json_encode($result));
            }

        }
        $result['status'] = '0';
        $result['message'] = 'Ch??a c?? template cho lo???i ??i???n tho???i b???n y??u c???u';

        $this->getResponse()->setHeader('Content-type', 'application/json');
        return $this->getResponse()->setBody(json_encode($result));
    }

    public function downloadMockupAction()
    {
        $mockup_file = $this->getRequest()->getParam('mockup_file');
        if ($mockup_file) {
            $path_image_download = Mage::helper('productupload')->getDirImageMockup() . $mockup_file;
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($path_image_download) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($path_image_download));
            readfile($path_image_download);
            exit;
        }

    }

    public function newMockupAction()
    {
        if (!Mage::helper('productupload')->isDesigner()) {
            $this->_redirect('customer/account/login');
            return;
        }
        $this->loadLayout();
        $this->renderLayout();
    }

    public function createNewMockupAction()
    {
        $session = $this->_getSession();
        if ($data = $this->getRequest()->getPost()) {

            if (isset($_FILES['mockup_file']['name']) && $_FILES['mockup_file']['name']) {


                try {
                    $uploader = new Varien_File_Uploader('mockup_file');
                    $uploader->setAllowedExtensions(array('psd'));
                    $uploader->setAllowRenameFiles(false);
                    $uploader->setFilesDispersion(false);

                    $path = Mage::helper('productupload')->getDirImageMockup();
                    $file_name = time() . $_FILES['mockup_file']['name'];
                    $result = $uploader->save($path, $file_name);
                    $data['mockup_file'] = $result['file'];
                } catch (Exception $e) {

                    $data['mockup_file'] = $_FILES['mockup_file']['name'];
                }
            }




            try {
                $productupload = Mage::getModel('productupload/mockup');
                $productupload->setData($data);
                $productupload->save();
                $session->addSuccess(("Chia s??? Mockup th??nh c??ng"));
                $this->_redirect('*/*/mockup');
                return;
            } catch (Exception $e) {
                Mage::log('Create new mockup ' . $e->getMessage(), null, 'mockup.log');
                $session->addError($this->__('Ch??a chia s??? ???????c thi???t k???. Vui l??ng th??? l???i'));
                $this->_redirect('*/*/mockup');
                return;
            }

        }
        $session->addError($this->__('Ch??a chia s??? ???????c mockup. Vui l??ng th??? l???i'));
        $this->_redirect('*/*/mockup');
        return;

    }

    public function deleteDesignAction(){
        $productupload_id = $this->getRequest()->getParam(('productupload_id'));

        $productupload = Mage::getModel('productupload/productupload')->load($productupload_id);
        $result = array();
        if($productupload && $productupload->getId() && $productupload->getStatus() != 1){
            $current_designer = Mage::helper('productupload')->getDesigner();
            if($current_designer && $current_designer->getId() && $current_designer->getId() == $productupload->getDesignerId()){
                try{
                    $productupload->delete();

                    $result['status'] = '1';
                    $this->_getSession()->addSuccess('Xo?? th??nh c??ng');
                    $this->getResponse()->setHeader('Content-type', 'application/json');
                    return $this->getResponse()->setBody(json_encode($result));
                    return;
                }catch (Exception $e){

                }

            }
            else{
                $result['status'] = '0';
                $result['message'] = 'B???n kh??ng c?? quy???n xo?? thi???t k??? n??y.';
                $this->getResponse()->setHeader('Content-type', 'application/json');
                return $this->getResponse()->setBody(json_encode($result));

            }
        }

        $result['status'] = '0';
        $result['message'] = 'Y??u c???u xo?? ch??a ???????c th???c hi???n. Vui l??ng th??? l???i sau';
        $this->getResponse()->setHeader('Content-type', 'application/json');
        return $this->getResponse()->setBody(json_encode($result));
    }

    public function rankAction()
    {
        if (!$this->_getSession()->isLoggedIn()) {
            $this->_redirect('customer/account/login');
            return;
        }

        if (!Mage::helper('productupload')->isDesigner()) {
            $this->_redirect('*/*/register');
            return;
        }

        $this->loadLayout();
        $this->renderLayout();
    }

}