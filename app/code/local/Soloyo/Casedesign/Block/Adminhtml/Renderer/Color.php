<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 9/11/18
 * Time: 9:16 PM
 */
class Soloyo_Casedesign_Block_Adminhtml_Renderer_Color extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract{
    public function render(Varien_Object $row)
    {
        $value = $row->getData($this->getColumn()->getIndex());
        return '<div  width="80" > <span style="background: '.$value.  '"' .'>'.$value. '</span> </div>';
    }
}