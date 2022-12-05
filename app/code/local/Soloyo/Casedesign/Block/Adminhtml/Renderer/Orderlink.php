<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 9/11/18
 * Time: 9:16 PM
 */
class Soloyo_Casedesign_Block_Adminhtml_Renderer_Orderlink extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract{
    public function render(Varien_Object $row)
    {
        $order_id = $row->getData($this->getColumn()->getIndex());

        return sprintf('<a href="%s">%s</a>', $this->getUrl('adminhtml/sales_order/view', array('order_id' => $order_id)), $order_id);


    }



}