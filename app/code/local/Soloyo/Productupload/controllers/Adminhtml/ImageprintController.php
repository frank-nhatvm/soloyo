<?php

class Soloyo_Productupload_Adminhtml_ImageprintController extends Mage_Adminhtml_Controller_Action
{

    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('productupload/imageprint')
            ->_addBreadcrumb(
                Mage::helper('adminhtml')->__('Images Print Manager'),
                Mage::helper('adminhtml')->__('Image Print Manager')
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
        $model = Mage::getModel('productupload/imageprint')->load($productuploadId);

        if ($model->getId() || $productuploadId == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }

            Mage::register('imageprint_data', $model);

            $this->loadLayout();
            $this->_setActiveMenu('productupload/imageprint');

            $this->_addBreadcrumb(
                Mage::helper('adminhtml')->__('Image Print Manager'),
                Mage::helper('adminhtml')->__('Image Print Manager')
            );
            $this->_addBreadcrumb(
                Mage::helper('adminhtml')->__('Image Print New'),
                Mage::helper('adminhtml')->__('Image Print New')
            );

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock('productupload/adminhtml_imageprint_edit'))
                ->_addLeft($this->getLayout()->createBlock('productupload/adminhtml_imageprint_edit_tabs'));

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

    protected function saveImageFile($key_file)
    {
            try {
                $uploader = new Varien_File_Uploader($key_file);
                $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
                $uploader->setAllowRenameFiles(false);
                $uploader->setFilesDispersion(false);

                $path = Mage::helper('productupload')->getDirImageProductUpload();

                $file_name = time() . $_FILES[$key_file]['name'];
                $result = $uploader->save($path, $file_name);
                return $result['file'];
            } catch (Exception $e) {
                return $_FILES[$key_file]['name'];
            }

    }

    /**
     * save item action
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost()) {

            if( isset($_FILES['image_product']) && $_FILES['image_product']){
                $data['image_product'] = $this->saveImageFile('image_product');
            }

            $model = Mage::getModel('productupload/imageprint');
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


    /**
     * delete item action
     */
    public function deleteAction()
    {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $model = Mage::getModel('productupload/imageprint');
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
        $productuploadIds = $this->getRequest()->getParam('imageprint');
        if (!is_array($productuploadIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($productuploadIds as $productuploadId) {
                    $productupload = Mage::getModel('productupload/imageprint')->load($productuploadId);
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
        $productuploadIds = $this->getRequest()->getParam('imageprint');
        if (!is_array($productuploadIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($productuploadIds as $productuploadId) {
                    Mage::getSingleton('productupload/imageprint')
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
        $fileName = 'imageprint.csv';
        $content = $this->getLayout()
            ->createBlock('productupload/adminhtml_imageprint_grid')
            ->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export grid item to XML type
     */
    public function exportXmlAction()
    {
        $fileName = 'designer.xml';
        $content = $this->getLayout()
            ->createBlock('productupload/adminhtml_imageprint_grid')
            ->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('imageprint');
    }

    protected function myHelper()
    {
        return Mage::helper('productupload');
    }

}