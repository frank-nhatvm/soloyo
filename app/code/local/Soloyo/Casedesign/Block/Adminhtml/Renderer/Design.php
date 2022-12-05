<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 9/11/18
 * Time: 9:16 PM
 */
class Soloyo_Casedesign_Block_Adminhtml_Renderer_Design extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract{
    public function render(Varien_Object $row)
    {
        $result = '<span style="font-weight: bold; color: #CDCDCD;text-transform: uppercase;">No Design</span>';
        $has_design = Mage::helper('casedesign/data')->hasDesignForProductId($row['entity_id']);
        if($has_design == 1) {
            $result = '<span style="font-weight: bold; text-transform: uppercase;">Has Design</span>';
        }

        return $result;
    }



}