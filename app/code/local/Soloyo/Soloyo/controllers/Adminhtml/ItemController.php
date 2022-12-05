<?php

class Soloyo_Soloyo_Adminhtml_ItemController extends Mage_Adminhtml_Controller_Action
{

    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('soloyo/item')
            ->_addBreadcrumb(
                Mage::helper('adminhtml')->__('Home Item Manager'),
                Mage::helper('adminhtml')->__('Home Item Manager')
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
        $model  = Mage::getModel('soloyo/homeitem')->load($homeId);

        if ($model->getId() || $homeId == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }
            Mage::register('homeitem_data', $model);

            $this->loadLayout();
            $this->_setActiveMenu('home/products');

            $this->_addBreadcrumb(
                Mage::helper('adminhtml')->__('Home Item Manager'),
                Mage::helper('adminhtml')->__('Home Item Manager')
            );
            $this->_addBreadcrumb(
                Mage::helper('adminhtml')->__('Home Item News'),
                Mage::helper('adminhtml')->__('Home Item News')
            );

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock('soloyo/adminhtml_item_edit'))
                ->_addLeft($this->getLayout()->createBlock('soloyo/adminhtml_item_edit_tabs'));

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

            if( (!isset($data['name']) || !$data['name']) && $data['product_id'] ){
                $product_id = $data['product_id'];
                $product = Mage::getModel('catalog/product')->load($product_id);
                $data['name'] = $product->getName();
            }

            if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != '') {
                $path_save = Mage::helper('soloyo')->getDirHomeItemImage();
                $data['image'] =  Mage::helper('soloyo')->getUrlHomeItemImage().Mage::helper('soloyo')->saveImage('image', $path_save);
            }else if($data['product_id']){
                if(!$product){
                    $product_id = $data['product_id'];
                    $product = Mage::getModel('catalog/product')->load($product_id);
                }
                $imageHelper = Mage::helper('catalog/image');
                $thumbail_src = $imageHelper->init($product, 'thumbnail')->resize(500,500)->__toString();
                $data['image'] = $thumbail_src;
            }



            $model = Mage::getModel('soloyo/homeitem');
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
                $model = Mage::getModel('soloyo/homeitem');
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
        $homeIds = $this->getRequest()->getParam('homeitem');
        if (!is_array($homeIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($homeIds as $homeId) {
                    $home = Mage::getModel('soloyo/homeitem')->load($homeId);
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
        $homeIds = $this->getRequest()->getParam('homeitem');
        if (!is_array($homeIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($homeIds as $homeId) {
                    Mage::getSingleton('soloyo/homeitem')
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
        $fileName   = 'homeitem.csv';
        $content    = $this->getLayout()
            ->createBlock('soloyo/adminhtml_item_grid')
            ->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export grid item to XML type
     */
    public function exportXmlAction()
    {
        $fileName   = 'homeitem.xml';
        $content    = $this->getLayout()
            ->createBlock('soloyo/adminhtml_item_grid')
            ->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('item');
    }
}