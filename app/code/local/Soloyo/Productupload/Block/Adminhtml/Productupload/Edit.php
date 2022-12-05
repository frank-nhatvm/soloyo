<?php


class Soloyo_Productupload_Block_Adminhtml_Productupload_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
        
        $this->_objectId = 'id';
        $this->_blockGroup = 'productupload';
        $this->_controller = 'adminhtml_productupload';
        
        $this->_updateButton('save', 'label', Mage::helper('productupload')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('productupload')->__('Delete Item'));
        
        $this->_addButton('saveandcontinue', array(
            'label'        => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'    => 'saveAndContinueEdit()',
            'class'        => 'save',
        ), -100);

        if ($this->canAddApproveButton())
        {
            $id = $this->getProductUploadId();
            $approve_url  = $this->getUrl('*/*/approve',array('productupload_id'=>$id,'_current'=>true));

            $this->_addButton('approve_design', array(
                'label'        => Mage::helper('adminhtml')->__('Approve'),
                'onclick'    =>'setLocation(\'' . $approve_url . '\')',
                'class'        => 'save',
            ), -100);

        }

            $this->_formScripts[] = "

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }


    protected function canAddApproveButton(){

        if(!$this->getProductUploadId()){
            return false;
        }

        $status = Mage::registry('productupload_data')->getStatus();

        if($status == Soloyo_Productupload_Model_Status::STATUS_APPROVED){
            return false;
        }

        return true;
    }

    protected function getProductUploadId(){

        if (Mage::registry('productupload_data') && Mage::registry('productupload_data')->getId())
        {
            return Mage::registry('productupload_data')->getId();
        }

        return null;
    }

    /**
     * get text to show in header when edit an item
     *
     * @return string
     */
    public function getHeaderText()
    {
        if (Mage::registry('productupload_data')
            && Mage::registry('productupload_data')->getId()
        ) {
            return Mage::helper('productupload')->__("Edit Item '%s'",
                                                $this->htmlEscape(Mage::registry('productupload_data')->getTitle())
            );
        }
        return Mage::helper('productupload')->__('Add Item');
    }
}