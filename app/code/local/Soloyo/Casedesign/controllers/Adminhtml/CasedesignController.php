<?php

class Soloyo_Casedesign_Adminhtml_CasedesignController extends Mage_Adminhtml_Controller_Action
{
    /**
     * init layout and set active for current menu
     *
     * @return Soloyo_Casedesign_Adminhtml_CasedesignController
     */
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('casedesign/casedesign')
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
        $id = $this->getRequest()->getParam('id');
        //$model  = Mage::getModel('casedesign/casedesign')->load($casedesignId);
        $model = Mage::getModel('catalog/product')->load($id);


        if ($model->getId() || $id != 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }

//            $collection =Mage::getModel('casedesign/casedesign')
//                ->getCollection();
//            $test = array();
//            foreach ($collection as $item){
//                $test[] = $item->toArray();
//            }
//            echo json_encode($test);
//            die('edit');

            $model_design = null;
            $case_design_collection = Mage::getModel('casedesign/casedesign')
                ->getCollection()->addFieldToFilter('product_id', $id);
            if (count($case_design_collection)) {
                foreach ($case_design_collection as $case_design) {
                    $case_design_id = $case_design->getId();
                    $model_design = Mage::getModel('casedesign/casedesign')->load($case_design_id);
                    break;
                }
            }

            Mage::register('casedesign_data', $model_design);

            $this->loadLayout();
            $this->_setActiveMenu('casedesign/casedesign');

            $this->_addBreadcrumb(
                Mage::helper('adminhtml')->__('Item Manager'),
                Mage::helper('adminhtml')->__('Item Manager')
            );
            $this->_addBreadcrumb(
                Mage::helper('adminhtml')->__('Item News'),
                Mage::helper('adminhtml')->__('Item News')
            );

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock('casedesign/adminhtml_casedesign_edit'))
                ->_addLeft($this->getLayout()->createBlock('casedesign/adminhtml_casedesign_edit_tabs'));

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('casedesign')->__('Item does not exist')
            );
            $this->_redirect('*/*/');
        }
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    protected function saveFile($file_key,$product_id)
    {
        if (isset($_FILES[$file_key]['name']) && $_FILES[$file_key]['name'] != '') {
            try {
                /* Starting upload */
                $uploader = new Varien_File_Uploader($file_key);

                // Any extention would work
                $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
                $uploader->setAllowRenameFiles(false);

                // Set the file upload mode
                // false -> get the file directly in the specified folder
                // true -> get the file in the product like folders
                //    (file.jpg will go in something like /media/f/i/file.jpg)
                $uploader->setFilesDispersion(false);

                // We set media as the upload dir
                $path = Mage::helper('casedesign')->getPathCasedesignTemplate($product_id);
                $result = $uploader->save($path, time().$_FILES[$file_key]['name']);
                return $result['file'];
            } catch (Exception $e) {
                return $_FILES[$file_key]['name'];
            }
        }
        return '';
    }

    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost()) {

            $product_id = $data['product_id'];

            if (isset($_FILES['overlay_image']['name']) && $_FILES['overlay_image']['name'] != '') {
                $data['overlay_image'] = $this->saveFile('overlay_image',$product_id);
            }

            $casedesign_id = null;
            if(isset($data['casedesign_id']) && $data['casedesign_id']){
                $casedesign_id = $data['casedesign_id'];
            }

            $model = Mage::getModel('casedesign/casedesign');
            $model->setData($data)->setId($casedesign_id);

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

    /**
     * delete item action
     */
    public function deleteAction()
    {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $model = Mage::getModel('casedesign/casedesign');
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
        $casedesignIds = $this->getRequest()->getParam('casedesign');
        if (!is_array($casedesignIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($casedesignIds as $casedesignId) {
                    $casedesign = Mage::getModel('casedesign/casedesign')->load($casedesignId);
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

    /**
     * mass change status for item(s) action
     */
    public function massStatusAction()
    {
        $casedesignIds = $this->getRequest()->getParam('casedesign');
        if (!is_array($casedesignIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($casedesignIds as $casedesignId) {
                    Mage::getSingleton('casedesign/casedesign')
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

    /**
     * export grid item to CSV type
     */
    public function exportCsvAction()
    {
        $fileName = 'casedesign.csv';
        $content = $this->getLayout()
            ->createBlock('casedesign/adminhtml_casedesign_grid')
            ->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export grid item to XML type
     */
    public function exportXmlAction()
    {
        $fileName = 'casedesign.xml';
        $content = $this->getLayout()
            ->createBlock('casedesign/adminhtml_casedesign_grid')
            ->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('casedesign');
    }
}