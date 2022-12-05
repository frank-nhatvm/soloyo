<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 9/11/18
 * Time: 9:16 PM
 */
class Soloyo_Casedesign_Block_Adminhtml_Renderer_Imageorderitems extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract{
    public function render(Varien_Object $row)
    {
        $value = $row->getData($this->getColumn()->getIndex());

        $image_path = null;

        $content_design = $row->getContentDesign();
        if($content_design){
            $image_path    = Mage::helper('casedesign')->getUrlPathCasedesignCustomer().$value;
        }
        else{
            $image_path   = Mage::helper('productupload')->getUrlImageProductUpload().$value;
        }

        return '<img src="'.$image_path.'"  width="80" /><a style="margin-left: 20px" href="'.$image_path.'">View Full Image</a>';


    }



}