<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 9/11/18
 * Time: 9:16 PM
 */
class Soloyo_Soloyo_Block_Adminhtml_Renderer_Bannerimage extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract{
    public function render(Varien_Object $row)
    {
        $value = $row->getData($this->getColumn()->getIndex());
        return '<img src="'.$this->helper('soloyo')->getUrlHomeBanerImage().$value.'"  width="80" />';
    }



}