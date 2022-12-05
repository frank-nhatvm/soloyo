<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 8/17/18
 * Time: 11:15 PM
 */
class Soloyo_Brandmodel_Adminhtml_ModelmobileController extends Mage_Adminhtml_Controller_Action
{
    /**
     * init layout and set active for current menu
     *
     * @return Soloyo_Brandmodel_Adminhtml_BrandmodelController
     */
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('brandmodel/modelmobile')
            ->_addBreadcrumb(
                Mage::helper('adminhtml')->__('Models Manager'),
                Mage::helper('adminhtml')->__('Model Manager')
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
        $brandmodelId     = $this->getRequest()->getParam('id');
        $model  = Mage::getModel('brandmodel/modelmobile')->load($brandmodelId);

        if ($model->getId() || $brandmodelId == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }
            Mage::register('modelmobile_data', $model);

            $this->loadLayout();
            $this->_setActiveMenu('brandmodel/modelmobile');

            $this->_addBreadcrumb(
                Mage::helper('adminhtml')->__('Model Manager'),
                Mage::helper('adminhtml')->__('Model Manager')
            );
            $this->_addBreadcrumb(
                Mage::helper('adminhtml')->__('Model News'),
                Mage::helper('adminhtml')->__('Model News')
            );

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock('brandmodel/adminhtml_modelmobile_edit'))
                ->_addLeft($this->getLayout()->createBlock('brandmodel/adminhtml_modelmobile_edit_tabs'));

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('brandmodel')->__('Model does not exist')
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

            $model = Mage::getModel('brandmodel/modelmobile');
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
                    Mage::helper('brandmodel')->__('Model was successfully saved')
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
            Mage::helper('brandmodel')->__('Unable to find item to save')
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
                $model = Mage::getModel('brandmodel/modelmobile');
                $model->setId($this->getRequest()->getParam('id'))
                    ->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__('Model was successfully deleted')
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
        $brandmodelIds = $this->getRequest()->getParam('modelmobile');
        if (!is_array($brandmodelIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select model(s)'));
        } else {
            try {
                foreach ($brandmodelIds as $brandmodelId) {
                    $brandmodel = Mage::getModel('brandmodel/modelmobile')->load($brandmodelId);
                    $brandmodel->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__('Total of %d record(s) were successfully deleted',
                        count($brandmodelIds))
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
        $brandmodelIds = $this->getRequest()->getParam('modelmobile');
        if (!is_array($brandmodelIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select model(s)'));
        } else {
            try {
                foreach ($brandmodelIds as $brandmodelId) {
                    Mage::getSingleton('brandmodel/modelmobile')
                        ->load($brandmodelId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($brandmodelIds))
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
        $fileName   = 'modelmobile.csv';
        $content    = $this->getLayout()
            ->createBlock('brandmodel/adminhtml_modelmobile_grid')
            ->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export grid item to XML type
     */
    public function exportXmlAction()
    {
        $fileName   = 'modelmobile.xml';
        $content    = $this->getLayout()
            ->createBlock('brandmodel/adminhtml_modelmobile_grid')
            ->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('modelmobile');
    }
}