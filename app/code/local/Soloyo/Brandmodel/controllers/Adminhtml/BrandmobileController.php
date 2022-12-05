<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 8/17/18
 * Time: 11:13 PM
 */

class Soloyo_Brandmodel_Adminhtml_BrandmobileController extends Mage_Adminhtml_Controller_Action
{
    /**
     * init layout and set active for current menu
     *
     * @return Soloyo_Brandmodel_Adminhtml_BrandmodelController
     */
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('brandmodel/brandmobile')
            ->_addBreadcrumb(
                Mage::helper('adminhtml')->__('Brands Manager'),
                Mage::helper('adminhtml')->__('Brand Manager')
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
        $model  = Mage::getModel('brandmodel/brandmobile')->load($brandmodelId);

        if ($model->getId() || $brandmodelId == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }

            $mobile_ids = $this->getModelMobileId($brandmodelId);
            $model->setData('model_mobile_ids',$mobile_ids);

            Mage::register('brandmobile_data', $model);

            $this->loadLayout();
            $this->_setActiveMenu('brandmodel/brandmobile');

            $this->_addBreadcrumb(
                Mage::helper('adminhtml')->__('Brand Manager'),
                Mage::helper('adminhtml')->__('Brand Manager')
            );
            $this->_addBreadcrumb(
                Mage::helper('adminhtml')->__('Brand News'),
                Mage::helper('adminhtml')->__('Brand News')
            );

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock('brandmodel/adminhtml_brandmobile_edit'))
                ->_addLeft($this->getLayout()->createBlock('brandmodel/adminhtml_brandmobile_edit_tabs'));

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('brandmodel')->__('Brand does not exist')
            );
            $this->_redirect('*/*/');
        }
    }

    protected function getModelMobileId($brand_id){
        $collection = Mage::getModel('brandmodel/modelmobile')
            ->getCollection()->addFieldToFilter('brandmobile_id',$brand_id);

        $ids = array();
        foreach ($collection as $entity){
            $ids[] = $entity->getBrandModelAttributeId();
        }

        return $ids;

    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function update_specialprice()
    {
        $collection = Mage::getModel('catalog/product')
            ->getCollection();
//        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($collection);
//        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($collection);

        Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
        if(count($collection)) {
            foreach ($collection as $item) {
                try {
                    $product = Mage::getModel('catalog/product')->load($item->getId());
                    if ($product->getCasedesign()) {
                    echo $product->getName();
                    echo '<br/>';
                    $product->setSpecialPrice(59000);
                    }
                    else{
                        $product->setSpecialPrice(0);
                    }


//                $product->setSpecialFromDate('2019-01-08');
//                $product->setSpecialFromDateIsFormated(true);
//
//                $product->setSpecialToDate('2019-01-25');
//                $product->setSpecialToDateIsFormated(true);

                    $product->save();
                } catch (Exception $e) {
                    echo 'exception ' . $e->getMessage();
                    echo '<br/>';
                }

            }
            echo 'done';
        }else{
            echo "empty";
        }
    }

    /**
     * save item action
     */
    public function saveAction()
    {

        if ($data = $this->getRequest()->getPost()) {

            $model = Mage::getModel('brandmodel/brandmobile');
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

                $brand_id = $model->getId();
                $model_mobile_ids = $data['model_mobile_ids'];


                foreach ($model_mobile_ids as $model_id){
                    $model_mobile = Mage::getModel('brandmodel/modelmobile')->getCollection()->addFieldToFilter('brand_model_attribute_id',$model_id)->getFirstItem();

                    if($model_mobile ){
                        $real_model = Mage::getModel('brandmodel/modelmobile')->load($model_mobile->getModelmobileId());
                        $real_model->setBrandmobileId($brand_id);
                        $real_model->save();
                    }
                }


                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('brandmodel')->__('Brand was successfully saved')
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
                $model = Mage::getModel('brandmodel/brandmobile');
                $model->setId($this->getRequest()->getParam('id'))
                    ->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__('Brand was successfully deleted')
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
        $brandmodelIds = $this->getRequest()->getParam('brandmobile');
        if (!is_array($brandmodelIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select brand(s)'));
        } else {
            try {
                foreach ($brandmodelIds as $brandmodelId) {
                    $brandmodel = Mage::getModel('brandmodel/brandmobile')->load($brandmodelId);
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
        $brandmodelIds = $this->getRequest()->getParam('brandmobile');
        if (!is_array($brandmodelIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select brand(s)'));
        } else {
            try {
                foreach ($brandmodelIds as $brandmodelId) {
                    Mage::getSingleton('brandmodel/brandmobile')
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
        $fileName   = 'brandmobile.csv';
        $content    = $this->getLayout()
            ->createBlock('brandmodel/adminhtml_brandmobile_grid')
            ->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export grid item to XML type
     */
    public function exportXmlAction()
    {
        $fileName   = 'brandmobile.xml';
        $content    = $this->getLayout()
            ->createBlock('brandmodel/adminhtml_brandmobile_grid')
            ->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('brandmobile');
    }
}