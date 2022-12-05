<?php

class Soloyo_Productupload_Adminhtml_TransactionController extends Mage_Adminhtml_Controller_Action
{

    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('productupload/designertransaction')
            ->_addBreadcrumb(
                Mage::helper('adminhtml')->__('Transaction Manager'),
                Mage::helper('adminhtml')->__('Transaction Manager')
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
        $model = Mage::getModel('productupload/designertransaction')->load($productuploadId);

        if ($model->getId() || $productuploadId == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }

            $designer_id = $model->getDesignerId();
            $designer = Mage::getModel('productupload/designer')->load($designer_id);
            $balance = $designer->getBalance();
            $user_id = $designer->getUserId();
            $customer = Mage::getModel('customer/customer')->load($user_id);
            $firstname = $customer->getFirstname();

            $model->setData('name',$firstname);
            $model->setData('balance',$balance);


            Mage::register('designertransaction_data', $model);

            $this->loadLayout();
            $this->_setActiveMenu('productupload/designertransaction');

            $this->_addBreadcrumb(
                Mage::helper('adminhtml')->__('Transaction Manager'),
                Mage::helper('adminhtml')->__('Transaction Manager')
            );
            $this->_addBreadcrumb(
                Mage::helper('adminhtml')->__('Transaction News'),
                Mage::helper('adminhtml')->__('Transaction News')
            );

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock('productupload/adminhtml_designertransaction_edit'))
                ->_addLeft($this->getLayout()->createBlock('productupload/adminhtml_designertransaction_edit_tabs'));

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

            $model = Mage::getModel('productupload/designertransaction');

            try {
                $status = $data['status'];

                if($status == 1){
                    // approved
                    if (isset($_FILES['image_transaction']['name']) && $_FILES['image_transaction']['name'] != '') {
                        try {
                            $uploader = new Varien_File_Uploader('image_transaction');
                            $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
                            $uploader->setAllowRenameFiles(false);
                            $uploader->setFilesDispersion(false);

                            $path =  $this->myHelper()->getDirImageDesingerTransaction();

                            $file_name = time() . $_FILES['image_transaction']['name'];
                            $result = $uploader->save($path, $file_name);
                            $data['image_transaction'] = $result['file'];
                        } catch (Exception $e) {
                            $data['image_transaction'] = $_FILES['image_transaction']['name'];
                        }
                    }
                }

                $model->setData($data)->setId($this->getRequest()->getParam('id'));;
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
                $model = Mage::getModel('productupload/designertransaction');
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
        $productuploadIds = $this->getRequest()->getParam('designertransaction');
        if (!is_array($productuploadIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($productuploadIds as $productuploadId) {
                    $productupload = Mage::getModel('productupload/designertransaction')->load($productuploadId);
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
        $productuploadIds = $this->getRequest()->getParam('designertransaction');
        if (!is_array($productuploadIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($productuploadIds as $productuploadId) {
                    Mage::getSingleton('productupload/designertransaction')
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
        $fileName = 'designertransaction.csv';
        $content = $this->getLayout()
            ->createBlock('productupload/adminhtml_designer_grid')
            ->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export grid item to XML type
     */
    public function exportXmlAction()
    {
        $fileName = 'designertransaction.xml';
        $content = $this->getLayout()
            ->createBlock('productupload/adminhtml_designer_grid')
            ->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('designertransaction');
    }

    protected function myHelper()
    {
        return Mage::helper('productupload');
    }

}