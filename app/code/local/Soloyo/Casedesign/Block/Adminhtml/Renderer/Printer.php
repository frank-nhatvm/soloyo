<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 9/11/18
 * Time: 9:16 PM
 */
class Soloyo_Casedesign_Block_Adminhtml_Renderer_Printer extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract{
    public function render(Varien_Object $row)
    {
        $printer_id = $row->getData($this->getColumn()->getIndex());
        $printer = Mage::getModel('printer/printer')->load($printer_id);
        $result = '<span style="font-weight: bold; color: #2d2d2d;text-transform: uppercase;">'.$printer->getName().'</span>';
        return $result;
    }



}