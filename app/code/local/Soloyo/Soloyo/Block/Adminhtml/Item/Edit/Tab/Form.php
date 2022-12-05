<?php

class Soloyo_Soloyo_Block_Adminhtml_Item_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare tab form's information
     *
     * @return Soloyo_Home_Block_Adminhtml_Home_Edit_Tab_Form
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);

        if (Mage::getSingleton('adminhtml/session')->getHomeitemsData()) {
            $data = Mage::getSingleton('adminhtml/session')->getHomeitemData();
            Mage::getSingleton('adminhtml/session')->setHomeitemData(null);
        } elseif (Mage::registry('homeitem_data')) {
            $data = Mage::registry('homeitem_data')->getData();
        }




        $fieldset = $form->addFieldset('homeitem_form', array(
            'legend'=>Mage::helper('soloyo')->__('Home Item information')
        ));

        $fieldset->addField('name', 'text', array(
            'label'        => Mage::helper('soloyo')->__('Name'),
            'name'        => 'name',
        ));

        $fieldset->addField('parent_id', 'text', array(
            'label'        => Mage::helper('soloyo')->__('Parent Id'),


            'name'        => 'parent_id',
        ));
        $fieldset->addField('product_id', 'text', array(
            'label'        => Mage::helper('soloyo')->__('Product Id'),


            'name'        => 'product_id',
        ));
        $fieldset->addField('cate_id', 'text', array(
            'label'        => Mage::helper('soloyo')->__('Category Id'),


            'name'        => 'cate_id',
        ));
        $fieldset->addField('url', 'text', array(
            'label'        => Mage::helper('soloyo')->__('Url'),


            'name'        => 'url',
        ));

        $fieldset->addField('image', 'file', array(
            'label'        => Mage::helper('soloyo')->__('Image'),
            'required'    => false,
            'name'        => 'image',
        ));

        $fieldset->addField('position', 'text', array(
            'label'        => Mage::helper('soloyo')->__('Position'),
            'required'    => true,
            'name'        => 'position',
        ));

        $values = array(array('value'=>0,'label'=>'Product'),array('value'=>1,'label'=>'Category'));

        $fieldset->addField('type', 'select', array(
            'label'        => Mage::helper('soloyo')->__('Type show'),
            'name'        => 'type',
            'values'    => $values,
        ));

        $fieldset->addField('status', 'select', array(
            'label'        => Mage::helper('soloyo')->__('Status'),
            'name'        => 'status',
            'values'    => Mage::getSingleton('soloyo/status')->getOptionHash(),
        ));

        $form->setValues($data);
        return parent::_prepareForm();
    }
}