<?php

class Soloyo_Casedesign_Block_Adminhtml_Casecolor_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare form's information for block
     *
     * @return Soloyo_Casedesign_Block_Adminhtml_Casedesign_Edit_Form
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(array(
            'id'        => 'edit_form',
            'action'    => $this->getUrl('*/*/save', array(
                'id'    => $this->getRequest()->getParam('id'),
            )),
            'method'    => 'post',
            'enctype'   => 'multipart/form-data'
        ));

        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }
}