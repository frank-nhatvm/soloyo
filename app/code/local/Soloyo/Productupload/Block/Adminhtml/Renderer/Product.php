<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 9/11/18
 * Time: 9:16 PM
 */
class Soloyo_Productupload_Block_Adminhtml_Renderer_Product extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract{
    public function render(Varien_Object $row)
    {
        $product_id = $row->getData($this->getColumn()->getIndex());

        $product = Mage::getModel('catalog/product')->load($product_id);

        $product_name = $product->getName();
        $product_url = $product->getProductUrl();


        return sprintf('<a href="%s">%s</a>', $product_url, $product_name);


    }



}