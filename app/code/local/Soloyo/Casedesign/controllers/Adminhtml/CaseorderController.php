<?php

class Soloyo_Casedesign_Adminhtml_CaseorderController extends Mage_Adminhtml_Controller_Action
{
    /**
     * init layout and set active for current menu
     *
     * @return Soloyo_Casedesign_Adminhtml_CasedesignController
     */
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('casedesign/caseorder')
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
        $casedesignId = $this->getRequest()->getParam('id');
        $model = Mage::getModel('casedesign/caseorder')->load($casedesignId);

        if ($model->getId() || $casedesignId == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }


            $case_order_items = $this->get_case_order_items($casedesignId);
            $model->setData('items', $case_order_items);

            Mage::register('caseorder_data', $model);

            $this->loadLayout();
            $this->_setActiveMenu('casedesign/caseorder');

            $this->_addBreadcrumb(
                Mage::helper('adminhtml')->__('Item Manager'),
                Mage::helper('adminhtml')->__('Item Manager')
            );
            $this->_addBreadcrumb(
                Mage::helper('adminhtml')->__('Item News'),
                Mage::helper('adminhtml')->__('Item News')
            );

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock('casedesign/adminhtml_caseorder_edit'))
                ->_addLeft($this->getLayout()->createBlock('casedesign/adminhtml_caseorder_edit_tabs'));

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('casedesign')->__('Item does not exist')
            );
            $this->_redirect('*/*/');
        }
    }

    protected function get_case_order_items($case_order_id)
    {
        $collection = Mage::getModel('casedesign/caseorderitem')
            ->getCollection()->addFieldToFilter('caseorder_id', $case_order_id);

        $result = array();
        foreach ($collection as $item) {
            $data_item = $item->toArray();
            $product_id = $item->getProductId();
            $product = Mage::getModel('catalog/product')->load($product_id);
            $product_name = $product->getName();
            $data_item['product_name'] = $product_name;
            $result[] = $data_item;
        }

        return $result;

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

            if (!isset($data['printer_id']) || !$data['printer_id']) {
                Mage::getSingleton('adminhtml/session')->addError('Please select a printer');
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
            $case_order_id = $this->getRequest()->getParam('id');
            $model = Mage::getModel('casedesign/caseorder');
            $model->setData($data)
                ->setId($case_order_id);
            $case_order_id_array = array();
            $case_order_id_array[] = $case_order_id;

            if($this->sendDesignToPrinter($case_order_id_array,$data['printer_id']))
            {
                $model->setStatus(1);
            }else{
                Mage::getSingleton('adminhtml/session')->addError('Send Design Fail. Please try again');
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }


            try {
                if ($model->getCreatedTime == NULL || $model->getUpdateTime() == NULL) {
                    $model->setCreatedTime(now())
                        ->setUpdateTime(now());
                } else {
                    $model->setUpdateTime(now());
                }
                $model->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('casedesign')->__('Item was successfully saved')
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
            Mage::helper('casedesign')->__('Unable to find item to save')
        );
        $this->_redirect('*/*/');
    }

    protected function sendDesignToPrinter($caseOrderArrray,$printer_id)
    {
        try {
            if(is_array($caseOrderArrray)){
                $caseOrderIds = implode(', ', $caseOrderArrray);
            }else{
                $caseOrderIds = $caseOrderArrray;
            }

            $emailTemplate = Mage::getModel('core/email_template')->load('1');
            $email_custom_data = array('caseorder_ids' => $caseOrderIds,'printer_id'=>$printer_id);
            $vars = array('email_custom_data' => $email_custom_data);
            $emailTemplate->getProcessedTemplate($vars);
            //     echo (  $emailTemplate->getProcessedTemplate($vars));
            //   die();
            $emailTemplate->setSenderName('Soloyo');
            $store_id = Mage::app()->getStore()->getId();
            $sender_email = Mage::getStoreConfig('trans_email/ident_general/email', $store_id);

            $emailTemplate->setSenderEmail($sender_email);
            $sender_name = Mage::getStoreConfig('trans_email/ident_general/name', $store_id);
            $emailTemplate->setSenderName($sender_name);

            $printer = Mage::getModel('printer/printer')->load($printer_id);
            $printer_email = $printer->getEmail();
            $printer_name = $printer->getName();

            if ($emailTemplate->send($printer_email, $printer_name, $vars)) {
                //echo 'Send Success sendder email '.$sender_email .' sender name '.$sender_name.'printer email '.$printer_email.' printer name '.$printer_name;
                return true;
            } else {
                return false;
            }

        } catch (Exception $e) {
            echo 'Send Fail '.$e->getMessage();
            return false;
        }
    }



    /**
     * delete item action
     */
    public function deleteAction()
    {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $model = Mage::getModel('casedesign/caseorder');
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


    public function massSendToPrinterAction(){
        $caseorderids = $this->getRequest()->getParam('caseorder');


        if (!is_array($caseorderids)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {

            $printer_id = $this->getRequest()->getParam('printer_id');

            if($this->sendDesignToPrinter($caseorderids,$printer_id)){

                foreach ($caseorderids as $caseorderid){
                    $case_order_model = Mage::getModel('casedesign/caseorder')->load($caseorderid);
                    $case_order_model->setPinterId($printer_id);
                    $case_order_model->setStatus(1);
                    if ($case_order_model->getCreatedTime == NULL || $case_order_model->getUpdateTime() == NULL) {
                        $case_order_model->setCreatedTime(now())
                            ->setUpdateTime(now());
                    } else {
                        $case_order_model->setUpdateTime(now());
                    }
                    $case_order_model->save();
                }

                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(' %d đơn hàng đã được gửi tới nhà in thành công',
                        count($caseorderids))
                );
            }else{
                Mage::getSingleton('adminhtml/session')->addError('Gửi đơn hàng tới nhà in thất bại');
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass delete item(s) action
     */
    public function massDeleteAction()
    {
        $casedesignIds = $this->getRequest()->getParam('caseorder');
        if (!is_array($casedesignIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($casedesignIds as $casedesignId) {
                    $casedesign = Mage::getModel('casedesign/caseorder')->load($casedesignId);
                    $casedesign->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__('Total of %d record(s) were successfully deleted',
                        count($casedesignIds))
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function orderitemsAction()
    {
        $this->loadLayout();
        $this->getLayout()->getBlock('casedesign.adminhtml.caseorder.edit.tab.orderitems')
            ->setCaseOrderId($this->getRequest()->getParam('case_order_id'));
        $this->renderLayout();
    }

    /**
     * mass change status for item(s) action
     */
    public function massStatusAction()
    {
        $casedesignIds = $this->getRequest()->getParam('caseorder');
        if (!is_array($casedesignIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($casedesignIds as $casedesignId) {
                    Mage::getSingleton('casedesign/caseorder')
                        ->load($casedesignId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($casedesignIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function downloadShippingInfoAction()
    {
        $id = $this->getRequest()->getParam('id');
        $case_model = Mage::getModel('casedesign/caseorder')->load($id);
        $order_id = $case_model->getOrderID();

        $order = Mage::getModel('sales/order')->load($order_id);
        $content = $order->getBillingAddress()->getFormated(true);

        $fileName = 'ship.txt';
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export grid item to CSV type
     */
    public function exportCsvAction()
    {
        $fileName = 'caseorder.csv';
        $content = $this->getLayout()
            ->createBlock('casedesign/adminhtml_caseorder_grid')
            ->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export grid item to XML type
     */
    public function exportXmlAction()
    {
        $fileName = 'caseorder.xml';
        $content = $this->getLayout()
            ->createBlock('casedesign/adminhtml_caseorder_grid')
            ->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('caseorder');
    }
}