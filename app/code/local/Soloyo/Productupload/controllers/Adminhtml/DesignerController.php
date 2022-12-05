<?php

class Soloyo_Productupload_Adminhtml_DesignerController extends Mage_Adminhtml_Controller_Action
{

    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('productupload/designer')
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
        $model = Mage::getModel('productupload/designer')->load($productuploadId);

        if ($model->getId() || $productuploadId == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }

            Mage::register('designer_data', $model);

            $this->loadLayout();
            $this->_setActiveMenu('productupload/designer');

            $this->_addBreadcrumb(
                Mage::helper('adminhtml')->__('Designer Manager'),
                Mage::helper('adminhtml')->__('Designer Manager')
            );
            $this->_addBreadcrumb(
                Mage::helper('adminhtml')->__('Designer News'),
                Mage::helper('adminhtml')->__('Designer News')
            );

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock('productupload/adminhtml_designer_edit'))
                ->_addLeft($this->getLayout()->createBlock('productupload/adminhtml_designer_edit_tabs'));

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

            $model = Mage::getModel('productupload/designer');
            $model->setData($data)
                ->setId($this->getRequest()->getParam('id'));

            try {
                if ($model->getCreatedTime == NULL || $model->getUpdateTime() == NULL) {
                    $model->setCreatedTime(now())
                        ->setUpdateTime(now());
                } else {
                    $model->setUpdateTime(now());
                }

                $designer = Mage::getModel('productupload/designer')->load($this->getRequest()->getParam('id'));
                if ($designer && $designer->getId()) {
                    $old_status = $designer->getStatus();
                    $new_status = $data['status'];
                    if ($old_status == 0 && $new_status == 1) {
                        $user_id = $designer->getUserId();

                        $user = Mage::getModel('customer/customer')->load($user_id);
                        if ($user && $user->getId()) {
                            $designer_name = $user->getFirstname();
                            $designer_email = $designer->getEmail();
                            $this->notifyToDesigner($designer_name, $designer_email);
                        }
                    }
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

    protected function notifyToDesigner($designer_name, $designer_email)
    {
        try {
            $emailTemplate = Mage::getModel('core/email_template')->load('3');
            $vars = array('designer_name' => $designer_name);
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

        }

    }

    public
    function casedesignAction()
    {
        $this->loadLayout();
        $this->getLayout()->getBlock('productupload.adminhtml.designer.edit.tab.casedesign')
            ->setDesignerId($this->getRequest()->getParam('designer_id'));
        $this->renderLayout();
    }

    public
    function transactionAction()
    {
        $this->loadLayout();
        $this->getLayout()->getBlock('productupload.adminhtml.designer.edit.tab.transaction')
            ->setDesignerId($this->getRequest()->getParam('designer_id'));
        $this->renderLayout();
    }


    /**
     * delete item action
     */
    public
    function deleteAction()
    {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $model = Mage::getModel('productupload/designer');
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
        $productuploadIds = $this->getRequest()->getParam('designer');
        if (!is_array($productuploadIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($productuploadIds as $productuploadId) {
                    $productupload = Mage::getModel('productupload/designer')->load($productuploadId);
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
        $productuploadIds = $this->getRequest()->getParam('designer');
        if (!is_array($productuploadIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($productuploadIds as $productuploadId) {
                    Mage::getSingleton('productupload/designer')
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
            ->createBlock('productupload/adminhtml_designer_grid')
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
            ->createBlock('productupload/adminhtml_designer_grid')
            ->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    protected
    function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('designer');
    }

    protected
    function myHelper()
    {
        return Mage::helper('productupload');
    }

}