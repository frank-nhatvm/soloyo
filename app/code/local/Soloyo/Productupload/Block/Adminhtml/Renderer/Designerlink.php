<?php

/**
 * Created by PhpStorm.
 * User: frank
 * Date: 9/11/18
 * Time: 9:16 PM
 */
class Soloyo_Productupload_Block_Adminhtml_Renderer_Designerlink extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $designer_id = $row->getData($this->getColumn()->getIndex());
        $designer = Mage::getModel('productupload/designer')->load($designer_id);
        $userId = $designer->getUserId();
        $user = Mage::getModel('customer/customer')->load($userId);
        $userName = $user->getFirstname();

        return sprintf('<a href="%s">%s</a>', $this->getUrl('productuploadadmin/adminhtml_designer/edit', array('id' => $designer_id)), $userName);


    }


}