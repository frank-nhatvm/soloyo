<?php

class Soloyo_Soloyo_Adminhtml_ProductsController extends Mage_Adminhtml_Controller_Action
{

    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('soloyo/products')
            ->_addBreadcrumb(
                Mage::helper('adminhtml')->__('Products Manager'),
                Mage::helper('adminhtml')->__('Products Manager')
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
        $homeId     = $this->getRequest()->getParam('id');
        $model  = Mage::getModel('soloyo/homeproducts')->load($homeId);

        if ($model->getId() || $homeId == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }
            Mage::register('homeproducts_data', $model);

            $this->loadLayout();
            $this->_setActiveMenu('soloyo/products');

            $this->_addBreadcrumb(
                Mage::helper('adminhtml')->__('Products Manager'),
                Mage::helper('adminhtml')->__('Products Manager')
            );
            $this->_addBreadcrumb(
                Mage::helper('adminhtml')->__('Products News'),
                Mage::helper('adminhtml')->__('Products News')
            );

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock('soloyo/adminhtml_products_edit'))
                ->_addLeft($this->getLayout()->createBlock('soloyo/adminhtml_products_edit_tabs'));

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('soloyo')->__('Item does not exist')
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
            if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != '') {
                $path_save = Mage::helper('soloyo')->getDirHomeProductsImage();
                $data['image'] = Mage::helper('soloyo')->saveImage('image', $path_save);
            }
              
            $model = Mage::getModel('soloyo/homeproducts');
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
                $parent_id = $model->getId();
                $items = $data['items'];
                if($items && count($items)){
                    foreach ($items as $item_id){
                        $item_model  = Mage::getModel('soloyo/homeitem')->load($item_id);
                        $item_model->setParentId($parent_id);
                        $item_model->save();
                    }
                }


                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('soloyo')->__('Item was successfully saved')
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
            Mage::helper('soloyo')->__('Unable to find item to save')
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
                $model = Mage::getModel('soloyo/homeproducts');
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
        $homeIds = $this->getRequest()->getParam('homeproducts');
        if (!is_array($homeIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($homeIds as $homeId) {
                    $home = Mage::getModel('soloyo/homeproducts')->load($homeId);
                    $home->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__('Total of %d record(s) were successfully deleted',
                    count($homeIds))
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
        $homeIds = $this->getRequest()->getParam('homeproducts');
        if (!is_array($homeIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($homeIds as $homeId) {
                    Mage::getSingleton('soloyo/homeproducts')
                        ->load($homeId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($homeIds))
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
        $fileName   = 'homeproducts.csv';
        $content    = $this->getLayout()
                           ->createBlock('soloyo/adminhtml_products_grid')
                           ->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export grid item to XML type
     */
    public function exportXmlAction()
    {
        $fileName   = 'homeproducts.xml';
        $content    = $this->getLayout()
                           ->createBlock('soloyo/adminhtml_products_grid')
                           ->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }
    
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('products');
    }
}