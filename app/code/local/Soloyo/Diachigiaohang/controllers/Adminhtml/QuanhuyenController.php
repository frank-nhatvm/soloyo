<?php

class Soloyo_Diachigiaohang_Adminhtml_QuanhuyenController extends Mage_Adminhtml_Controller_Action
{
    /**
     * init layout and set active for current menu
     *
     * @return Soloyo_Diachigiaohang_Adminhtml_DiachigiaohangController
     */
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('diachigiaohang/quanhuyen')
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
        $diachigiaohangId     = $this->getRequest()->getParam('id');
        $model  = Mage::getModel('diachigiaohang/quanhuyen')->load($diachigiaohangId);

        if ($model->getId() || $diachigiaohangId == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }

            $xaphuong_ids = $this->getXaphuongIds($diachigiaohangId);
            $model->setData('xaphuong_ids',$xaphuong_ids);
            Mage::register('quanhuyen_data', $model);

            $this->loadLayout();
            $this->_setActiveMenu('diachigiaohang/quanhuyen');

            $this->_addBreadcrumb(
                Mage::helper('adminhtml')->__('Item Manager'),
                Mage::helper('adminhtml')->__('Item Manager')
            );
            $this->_addBreadcrumb(
                Mage::helper('adminhtml')->__('Item News'),
                Mage::helper('adminhtml')->__('Item News')
            );

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock('diachigiaohang/adminhtml_quanhuyen_edit'))
                ->_addLeft($this->getLayout()->createBlock('diachigiaohang/adminhtml_quanhuyen_edit_tabs'));

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('diachigiaohang')->__('Item does not exist')
            );
            $this->_redirect('*/*/');
        }
    }

    protected function getXaphuongIds($quanhuyen_id){
        $collection = Mage::getModel('diachigiaohang/xaphuong')
            ->getCollection()->addFieldToFilter('quanhuyen_id',$quanhuyen_id);

        $ids = array();
        foreach ($collection as $entity){
            $ids[] = $entity->getId();
        }

        return $ids;
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

            $model = Mage::getModel('diachigiaohang/quanhuyen');
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

                $quanhuyen_id = $model->getId();
                $xaphuong_ids = $data['xaphuong_ids'];
                foreach ($xaphuong_ids as $xaphuong_id){
                    $xaphuong_model = Mage::getModel('diachigiaohang/xaphuong')->load($xaphuong_id);
                    $xaphuong_model->setQuanhuyenId($quanhuyen_id);
                    $xaphuong_model->save();
                }

                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('diachigiaohang')->__('Item was successfully saved')
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
            Mage::helper('diachigiaohang')->__('Unable to find item to save')
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
                $model = Mage::getModel('diachigiaohang/quanhuyen');
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
        $diachigiaohangIds = $this->getRequest()->getParam('quanhuyen');
        if (!is_array($diachigiaohangIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($diachigiaohangIds as $diachigiaohangId) {
                    $diachigiaohang = Mage::getModel('diachigiaohang/quanhuyen')->load($diachigiaohangId);
                    $diachigiaohang->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__('Total of %d record(s) were successfully deleted',
                    count($diachigiaohangIds))
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
        $diachigiaohangIds = $this->getRequest()->getParam('quanhuyen');
        if (!is_array($diachigiaohangIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($diachigiaohangIds as $diachigiaohangId) {
                    Mage::getSingleton('diachigiaohang/quanhuyen')
                        ->load($diachigiaohangId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($diachigiaohangIds))
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
        $fileName   = 'quanhuyen.csv';
        $content    = $this->getLayout()
                           ->createBlock('diachigiaohang/adminhtml_quanhuyen_grid')
                           ->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export grid item to XML type
     */
    public function exportXmlAction()
    {
        $fileName   = 'quanhuyen.xml';
        $content    = $this->getLayout()
                           ->createBlock('diachigiaohang/adminhtml_quanhuyen_grid')
                           ->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }
    
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('quanhuyen');
    }
}