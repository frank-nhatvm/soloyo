<?php

class Soloyo_Soloyo_Block_Adminhtml_Products_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
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
        
        if (Mage::getSingleton('adminhtml/session')->getHomeproductsData()) {
            $data = Mage::getSingleton('adminhtml/session')->getHomeproductsData();
            Mage::getSingleton('adminhtml/session')->setHomeproductsData(null);
        } elseif (Mage::registry('homeproducts_data')) {
            $data = Mage::registry('homeproducts_data')->getData();
        }

        $fieldset = $form->addFieldset('homeproducts_form', array(
            'legend'=>Mage::helper('soloyo')->__('Product list information')
        ));

        $fieldset->addField('name', 'text', array(
            'label'        => Mage::helper('soloyo')->__('Name'),
            'class'        => 'required-entry',
            'required'    => true,
            'name'        => 'name',
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

        $values = array(array('value'=>0,'label'=>'Show as product list'),array('value'=>1,'label'=>'Show as banner'));

        $fieldset->addField('type_show', 'select', array(
            'label'        => Mage::helper('soloyo')->__('Type show'),
            'name'        => 'type_show',
            'values'    => $values,
        ));

        $fieldset->addField('status', 'select', array(
            'label'        => Mage::helper('soloyo')->__('Status'),
            'name'        => 'status',
            'values'    => Mage::getSingleton('soloyo/status')->getOptionHash(),
        ));

        $fieldset->addField('items[]', 'multiselect', array(
            'label'        => Mage::helper('soloyo')->__('Items'),
            'name'        => 'items[]',
            'values'    => $this->getItems(),
        ));


        $form->setValues($data);
        return parent::_prepareForm();
    }


    protected function getItems(){
        $collection = Mage::getModel('soloyo/homeitem')->getCollection()
            ->addFieldToFilter('status','1')->addFieldToFilter('type','0');

        $result = array();
        foreach ($collection as $item){
            $array = array();
            $array['label'] = $item->getName();
            $array['value'] = $item->getId();
            $result[] = $array;
        }

        return $result;

    }

}