<?php

class Soloyo_Productupload_Adminhtml_RequestproductController extends Mage_Adminhtml_Controller_Action
{

    protected function _initAction()
    {

        $this->loadLayout()
            ->_setActiveMenu('productupload/requestproduct')
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
        $model = Mage::getModel('productupload/requestproduct')->load($productuploadId);

        if ($model->getId() || $productuploadId == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }

            Mage::register('requestproduct_data', $model);

            $this->loadLayout();
            $this->_setActiveMenu('productupload/requestproduct');

            $this->_addBreadcrumb(
                Mage::helper('adminhtml')->__('Request Product Manager'),
                Mage::helper('adminhtml')->__('Request Product Manager')
            );
            $this->_addBreadcrumb(
                Mage::helper('adminhtml')->__('Requirement News'),
                Mage::helper('adminhtml')->__('Requirement News')
            );

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock('productupload/adminhtml_requestproduct_edit'))
                ->_addLeft($this->getLayout()->createBlock('productupload/adminhtml_requestproduct_edit_tabs'));

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

            $model = Mage::getModel('productupload/requestproduct');
            $model->setData($data)
                ->setId($this->getRequest()->getParam('id'));

            try {


                if ($model->getCreatedTime == NULL || $model->getUpdateTime() == NULL) {
                    $model->setCreatedTime(now())
                        ->setUpdateTime(now());
                } else {
                    $model->setUpdateTime(now());
                }

                $requestproduct = Mage::getModel('productupload/requestproduct')->load($this->getRequest()->getParam('id'));
                if ($requestproduct && $requestproduct->getId()) {
                    $old_status = $requestproduct->getIsSend();
                    $new_status = $data['status'];
                    if ($old_status == 0 && $new_status == 1) {
                        $this->notifyToDesigner($data);
                        $data['is_send'] = '1';
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

    protected function notifyToDesigner($data)
    {
        $product_id = $data['product_id'];
        $product = Mage::getModel('catalog/product')->load($product_id);
        $product_name = $product->getName();
        $product_url = $product->getProductUrl();

        $requirement = $data['requirement'];

        $designer_id = $data['designer_id'];

        $designer = Mage::getModel('productupload/designer')->load($designer_id);
        $designer_user_id = $designer->getUserId();
        $designer_user = Mage::getModel('customer/customer')->load($designer_user_id);
        $designer_email = $designer_user->getEmail();
        $designer_name = $designer_user->getFirstname();

        try {
            $emailTemplate = Mage::getModel('core/email_template')->load('4');
            $custom_designer_data = array();
            $custom_designer_data['product_name'] = $product_name;
            $custom_designer_data['product_url'] = $product_url;
            $custom_designer_data['designer_name']= $designer_name;
            $custom_designer_data['requirement'] = $requirement;

            $vars = array('custom_designer_data' => $custom_designer_data);
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

    /**
     * delete item action
     */
    public
    function deleteAction()
    {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $model = Mage::getModel('productupload/requestproduct');
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
        $productuploadIds = $this->getRequest()->getParam('requestproduct');
        if (!is_array($productuploadIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($productuploadIds as $productuploadId) {
                    $productupload = Mage::getModel('productupload/requestproduct')->load($productuploadId);
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
        $productuploadIds = $this->getRequest()->getParam('requestproduct');
        if (!is_array($productuploadIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($productuploadIds as $productuploadId) {
                    Mage::getSingleton('productupload/requestproduct')
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
            ->createBlock('productupload/adminhtml_requestproduct_grid')
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
            ->createBlock('productupload/adminhtml_requestproduct_grid')
            ->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    protected
    function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('requestproduct');
    }

    protected
    function myHelper()
    {
        return Mage::helper('productupload');
    }

}