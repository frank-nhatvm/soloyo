<?php

class Soloyo_Casedesign_Block_Adminhtml_Casedesign_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare tab form's information
     *
     * @return Soloyo_Casedesign_Block_Adminhtml_Casedesign_Edit_Tab_Form
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);

        if (Mage::getSingleton('adminhtml/session')->getCasedesignData()) {
            $data = Mage::getSingleton('adminhtml/session')->getCasedesignData();
            Mage::getSingleton('adminhtml/session')->setCasedesignData(null);
        } elseif (Mage::registry('casedesign_data')) {
            $data = Mage::registry('casedesign_data')->getData();
        }
        $fieldset = $form->addFieldset('casedesign_form', array(
            'legend'=>Mage::helper('casedesign')->__('Design information')
        ));


        $fieldset->addField('product_id', 'select', array(
            'label'        => Mage::helper('casedesign')->__('Product'),
            'class'        => 'required-entry',
            'required'    => true,
            'values' => $this->get_case_design_product(),
            'name'        => 'product_id',
        ));

        $fieldset->addField('casedesign_id', 'hidden', array(
            'label'        => Mage::helper('casedesign')->__('ID'),

            'name'        => 'casedesign_id',
        ));

        $fieldset->addField('design_area_width', 'text', array(
            'label'        => Mage::helper('casedesign')->__('Real width'),
            'required'    => true,
            'name'        => 'design_area_width',
        ));

        $fieldset->addField('design_area_height', 'text', array(
            'label'        => Mage::helper('casedesign')->__('Real height'),
            'required'    => true,
            'name'        => 'design_area_height',
        ));

        if(isset($data['overlay_image']) && $data['overlay_image']){
            $path = Mage::helper('casedesign')->getUrlPathCasedesignTemplate($data['product_id']);
            $overlay_image_file = $data['overlay_image'];
            $overlay_image_path = $path.$overlay_image_file;
        }

        $fieldset->addField('overlay_image', 'file', array(
            'label'        => Mage::helper('casedesign')->__('Overlay image'),
            'required'    => false,
            'name'        => 'overlay_image',
            'after_element_html' => '<img src="' .$overlay_image_path.'"  style="width:56px" />'
        ));

        $fieldset->addField('status', 'select', array(
            'label'        => Mage::helper('casedesign')->__('Status'),
            'name'        => 'status',
            'values'    => Mage::getSingleton('casedesign/status')->getOptionHash(),
        ));


        $form->setValues($data);
        return parent::_prepareForm();
    }

    public function getCaseDesignData(){
        if (Mage::getSingleton('adminhtml/session')->getCasedesignData()) {
            $data = Mage::getSingleton('adminhtml/session')->getCasedesignData();
            Mage::getSingleton('adminhtml/session')->setCasedesignData(null);
        } elseif (Mage::registry('casedesign_data')) {
            $data = Mage::registry('casedesign_data')->getData();
        }

        return $data;
    }

    public function get_case_design_product(){
        $collection = Mage::helper('casedesign')->getCaseDesignProduct();

        $result = array();
        $result[] = array('value'=>'null','label'=>'Select a product');
        foreach ($collection as $product){
            $item = array();
            $item['value'] = $product->getId();
            $item['label'] = $product->getName();
            $result[] = $item;
        }

        return $result;

    }

}