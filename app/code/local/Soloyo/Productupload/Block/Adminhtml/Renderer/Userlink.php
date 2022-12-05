<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 9/11/18
 * Time: 9:16 PM
 */
class Soloyo_Productupload_Block_Adminhtml_Renderer_Userlink extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract{
    public function render(Varien_Object $row)
    {
        $userId = $row->getData($this->getColumn()->getIndex());

        $user = Mage::getModel('customer/customer')->load($userId);
        $userName = $user->getFirstname();

        return sprintf('<a href="%s">%s</a>', $this->getUrl('adminhtml/customer/edit', array('id' => $userId)), $userName);


    }



}