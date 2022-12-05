<?php
/**
 * Magestore
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Magestore.com license that is
 * available through the world-wide-web at this URL:
 * http://www.magestore.com/license-agreement.html
 * 
 * DISCLAIMER
 * 
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 * 
 * @category    Magestore
 * @package     Magestore_Menutop
 * @copyright   Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license     http://www.magestore.com/license-agreement.html
 */

/**
 * Menutop Edit Form Content Tab Block
 * 
 * @category    Magestore
 * @package     Magestore_Menutop
 * @author      Magestore Developer
 */
class Soloyo_Menutop_Block_Adminhtml_Menutop_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare tab form's information
     *
     * @return Soloyo_Menutop_Block_Adminhtml_Menutop_Edit_Tab_Form
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        
        if (Mage::getSingleton('adminhtml/session')->getMenutopData()) {
            $data = Mage::getSingleton('adminhtml/session')->getMenutopData();
            Mage::getSingleton('adminhtml/session')->setMenutopData(null);
        } elseif (Mage::registry('menutop_data')) {
            $data = Mage::registry('menutop_data')->getData();
        }
        $fieldset = $form->addFieldset('menutop_form', array(
            'legend'=>Mage::helper('menutop')->__('Item information')
        ));

        $fieldset->addField('menutop_cat_id', 'select', array(
            'name' => 'menutop_cat_id',
            'label' => Mage::helper('menutop')->__('Category'),
            'title' => Mage::helper('menutop')->__('Category'),
            'values' => Mage::helper('menutop')->getAllCategory(),
        ));

        $fieldset->addField('menu_name', 'text', array(
            'label'        => Mage::helper('menutop')->__('Name'),

            'name'        => 'menu_name',
        ));

        $fieldset->addField('menu_url', 'text', array(
            'label'        => Mage::helper('menutop')->__('Url'),
            'name'        => 'menu_url',
        ));

        $fieldset->addField('menutop_position', 'text', array(
            'label'        => Mage::helper('menutop')->__('Position'),
            'class'        => 'required-entry',
            'required'    => true,
            'name'        => 'menutop_position',
        ));


        $fieldset->addField('status', 'select', array(
            'label'        => Mage::helper('menutop')->__('Status'),
            'name'        => 'status',
            'values'    => Mage::getSingleton('menutop/status')->getOptionHash(),
        ));


        $form->setValues($data);
        return parent::_prepareForm();
    }
}