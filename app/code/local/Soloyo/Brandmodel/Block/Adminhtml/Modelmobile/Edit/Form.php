<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 8/18/18
 * Time: 9:14 AM
 */
class Soloyo_Brandmodel_Block_Adminhtml_Modelmobile_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{

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