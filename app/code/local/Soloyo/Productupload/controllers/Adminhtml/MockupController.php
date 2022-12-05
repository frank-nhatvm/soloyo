<?php

class Soloyo_Productupload_Adminhtml_MockupController extends Mage_Adminhtml_Controller_Action
{

    protected function _initAction()
    {

        $this->loadLayout()
            ->_setActiveMenu('productupload/mockup')
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
        $model = Mage::getModel('productupload/mockup')->load($productuploadId);

        if ($model->getId() || $productuploadId == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }

            Mage::register('mockup_data', $model);

            $this->loadLayout();
            $this->_setActiveMenu('productupload/mockup');

            $this->_addBreadcrumb(
                Mage::helper('adminhtml')->__('Mockup Manager'),
                Mage::helper('adminhtml')->__('Mockup Manager')
            );
            $this->_addBreadcrumb(
                Mage::helper('adminhtml')->__('Mockup News'),
                Mage::helper('adminhtml')->__('Mockup News')
            );

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock('productupload/adminhtml_mockup_edit'))
                ->_addLeft($this->getLayout()->createBlock('productupload/adminhtml_mockup_edit_tabs'));

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
            if (isset($_FILES['mockup_file']['name']) && $_FILES['mockup_file']['name'] != '') {
                $data['mockup_file'] = $this->saveImageFile('mockup_file');
            }
            $model = Mage::getModel('productupload/mockup');
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


    public function saveImageFile($key_file)
    {
        if (isset($_FILES[$key_file]['name']) && $_FILES[$key_file]['name'] != '') {
            try {
                $uploader = new Varien_File_Uploader($key_file);
                $uploader->setAllowedExtensions(array('psd'));
                $uploader->setAllowRenameFiles(false);
                $uploader->setFilesDispersion(false);

                $path = Mage::helper('productupload')->getDirImageMockup();
                $file_name = time() . $_FILES[$key_file]['name'];
                $result = $uploader->save($path, $file_name);
                return $result['file'];
            } catch (Exception $e) {


                return $_FILES[$key_file]['name'];
            }
        }
        return '';
    }


    /**
     * delete item action
     */
    public
    function deleteAction()
    {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $model = Mage::getModel('productupload/mockup');
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
    public
    function massDeleteAction()
    {
        $productuploadIds = $this->getRequest()->getParam('mockup');
        if (!is_array($productuploadIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($productuploadIds as $productuploadId) {
                    $productupload = Mage::getModel('productupload/mockup')->load($productuploadId);
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
    public
    function massStatusAction()
    {
        $productuploadIds = $this->getRequest()->getParam('mockup');
        if (!is_array($productuploadIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($productuploadIds as $productuploadId) {
                    Mage::getSingleton('productupload/mockup')
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
    public
    function exportCsvAction()
    {
        $fileName = 'designer.csv';
        $content = $this->getLayout()
            ->createBlock('productupload/adminhtml_mockup_grid')
            ->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export grid item to XML type
     */
    public
    function exportXmlAction()
    {
        $fileName = 'designer.xml';
        $content = $this->getLayout()
            ->createBlock('productupload/adminhtml_mockup_grid')
            ->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    protected
    function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('mockup');
    }

    protected
    function myHelper()
    {
        return Mage::helper('productupload');
    }

}