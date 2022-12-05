<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 9/11/18
 * Time: 9:16 PM
 */
class Soloyo_Casedesign_Block_Adminhtml_Renderer_Image extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract{
    public function render(Varien_Object $row)
    {
        $value = $row->getData($this->getColumn()->getIndex());

        $array_value = explode(', ',$value);
        if($array_value && is_array($array_value) && count($array_value)){

            $html = '<div>';

            foreach ($array_value as $image_link){
                $html = $html  . '<div>';
                $html = $html . '<img src="'.$this->helper('casedesign')->getUrlPathCasedesignCustomer().$image_link.'"  width="80" />';
                $html = $html . '</div>';
            }
            $html = $html . '</div>';

            return $html;
        }

        return '<img src="'.$this->helper('casedesign')->getUrlPathCasedesignCustomer().$value.'"  width="80" />';
    }



}