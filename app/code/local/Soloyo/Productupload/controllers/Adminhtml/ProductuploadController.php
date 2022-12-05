<?php

class Soloyo_Productupload_Adminhtml_ProductuploadController extends Mage_Adminhtml_Controller_Action
{
    /**
     * init layout and set active for current menu
     *
     * @return Soloyo_Productupload_Adminhtml_ProductuploadController
     */
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('productupload/productupload')
            ->_addBreadcrumb(
                Mage::helper('adminhtml')->__('Items Manager'),
                Mage::helper('adminhtml')->__('Item Manager')
            );
        return $this;
    }

    /**
     * index action
     */
    public function indexAction()
    {
        $this->_initAction()
            ->renderLayout();
    }

    /**
     * view and edit item action
     */
    public function editAction()
    {
        $productuploadId = $this->getRequest()->getParam('id');
        $model = Mage::getModel('productupload/productupload')->load($productuploadId);

        if ($model->getId() || $productuploadId == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {



                $model->setData($data);
            }



            Mage::register('productupload_data', $model);

            $this->loadLayout();
            $this->_setActiveMenu('productupload/productupload');

            $this->_addBreadcrumb(
                Mage::helper('adminhtml')->__('Item Manager'),
                Mage::helper('adminhtml')->__('Item Manager')
            );
            $this->_addBreadcrumb(
                Mage::helper('adminhtml')->__('Item News'),
                Mage::helper('adminhtml')->__('Item News')
            );

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock('productupload/adminhtml_productupload_edit'))
                ->_addLeft($this->getLayout()->createBlock('productupload/adminhtml_productupload_edit_tabs'));

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('productupload')->__('Item does not exist')
            );
            $this->_redirect('*/*/');
        }
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    /**
     * save item action
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost()) {

            // admin upload image from backend, designer uploaded image from frontend so these image was saved.
            if ($this->isShouldSaveImage($data)) {
                $data['image_product'] = $this->saveImageFile('image_product', true);
                $data['image_cate'] = $this->saveImageFile('image_cate', false);
            }


            $model = Mage::getModel('productupload/productupload');
            $model->setData($data)
                ->setId($this->getRequest()->getParam('id'));

            try {
                if ($model->getCreatedTime == NULL || $model->getUpdateTime() == NULL) {
                    $model->setCreatedTime(now())
                        ->setUpdateTime(now());
                } else {
                    $model->setUpdateTime(now());
                }
                $model->save();


                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('productupload')->__('Item was successfully saved')
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('productupload')->__('Unable to find item to save')
        );
        $this->_redirect('*/*/');
    }

    protected function isShouldSaveImage($data)
    {

        if (isset($data['is_admin']) && $data['is_admin'] == 1) {
            return true;
        }

        return false;

    }

    protected function saveImageFile($key_file, $is_product = true)
    {

        if (isset($_FILES[$key_file]['name']) && $_FILES[$key_file]['name'] != '') {
            try {
                $uploader = new Varien_File_Uploader($key_file);
                $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
                $uploader->setAllowRenameFiles(false);
                $uploader->setFilesDispersion(false);

                $path = $this->myHelper()->getDirImageProductUpload();
                if (!$is_product) {
                    // save image for category
                    $path = $this->myHelper()->getDirImageCategoryUpload();
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


    public function approveAction()
    {
        $product_upload_id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('productupload/productupload')->load($product_upload_id);
        if ($model->getId() && $model->getStatus() == Soloyo_Productupload_Model_Status::STATUS_PENDING) {
            $cat_id = $model->getCateId();
            try {

                $product_id = $this->saveProduct($model, $cat_id);

                $this->saveImageprint($model,$product_id);

                $model->setCateId($cat_id);
                $model->setProductId($product_id);
                $model->setStatus(1);
                $model->save();

            }catch (Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }

            Mage::getSingleton('adminhtml/session')->addSuccess(
                Mage::helper('productupload')->__('Item was successfully saved')
            );
            Mage::getSingleton('adminhtml/session')->setFormData(false);

            $this->_redirect('*/*/');
            return;
        }

        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('productupload')->__('This item can not be saved.')
        );
        $this->_redirect('*/*/');

    }


    protected function saveProduct($data, $cat_id)
    {
        $store_id = 0;
        $type_id = 'simple';
        $weight = 0.5;
        $visibility = 4;
        $qty = 10000;
        $status = 1;
        $AttributeSetId = 4; // default
        $thumbnail_url = $this->myHelper()->getDirImageProductUpload() . $data->getImageProduct();
        $product = Mage::getModel('catalog/product');
        if($old_product_id = $data->getProductId()){
            $product = Mage::getModel('catalog/product')->load($old_product_id);
        }

        $cat_ids = array();

        $cat_ids[] = $cat_id;

        $product
            ->setStoreId($store_id)
            ->setWebsiteIds(array(1))
            ->setTypeId($type_id)
            ->setCreatedAt(strtotime('now'))
            ->setSku($data->getProductSku())
            ->setName($data->getProductName())
            ->setAttributeSetId($AttributeSetId)
            ->setWeight($weight)
            ->setStatus($status)
            ->setTaxClassId(0)
            ->setPrice($data->getPrice())
            ->setDescription($data->getDescription())
            ->setShortDescription($data->getShortDescription())
            ->setVisibility($visibility)
            ->setBrand($data->getBrandId())
            ->setBrandModel($data->getModelId())
            ->setDesignerId($data->getDesignerId())
            ->setUrlKey($data->getUrlKey())
            ->setStockData(array(
                    'use_config_manage_stock' => 0,
                    'manage_stock' => 1,
                    'is_in_stock' => 1,
                    'qty' => $qty
                )
            )
            ->addImageToMediaGallery($thumbnail_url, array('image', 'thumbnail', 'small_image'), false, false)
            ->setCategoryIds($cat_ids);
        Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
        $product->save();

        return $product->getId();
    }

    protected function saveImageprint($data,$product_id){
        $model = Mage::getModel('productupload/imageprint');
        $model->setProductId($product_id);
        $productupload_id = $data->getProductuploadId();
        $model->setProductuploadId($productupload_id);
        $image_product = $data->getImagePrint();
        $model->setImageProduct($image_product);
        $model->save();
    }

    /**
     * delete item action
     */
    public function deleteAction()
    {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $model = Mage::getModel('productupload/productupload');
                $model->setId($this->getRequest()->getParam('id'))
                    ->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__('Item was successfully deleted')
                );
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }

    /**
     * mass delete item(s) action
     */
    public function massDeleteAction()
    {
        $productuploadIds = $this->getRequest()->getParam('productupload');
        if (!is_array($productuploadIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($productuploadIds as $productuploadId) {
                    $productupload = Mage::getModel('productupload/productupload')->load($productuploadId);
                    $productupload->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__('Total of %d record(s) were successfully deleted',
                        count($productuploadIds))
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass change status for item(s) action
     */
    public function massStatusAction()
    {
        $productuploadIds = $this->getRequest()->getParam('productupload');
        if (!is_array($productuploadIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($productuploadIds as $productuploadId) {
                    Mage::getSingleton('productupload/productupload')
                        ->load($productuploadId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($productuploadIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * export grid item to CSV type
     */
    public function exportCsvAction()
    {
        $fileName = 'productupload.csv';
        $content = $this->getLayout()
            ->createBlock('productupload/adminhtml_productupload_grid')
            ->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export grid item to XML type
     */
    public function exportXmlAction()
    {
        $fileName = 'productupload.xml';
        $content = $this->getLayout()
            ->createBlock('productupload/adminhtml_productupload_grid')
            ->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('productupload');
    }

    protected function myHelper()
    {
        return Mage::helper('productupload');
    }

}