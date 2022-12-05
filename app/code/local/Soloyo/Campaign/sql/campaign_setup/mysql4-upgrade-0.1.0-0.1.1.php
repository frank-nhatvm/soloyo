<?php
$installer = $this;
/* @var $installer Mage_Customer_Model_Entity_Setup */
$installer->startSetup();



$store = Mage::app()->getStore(Mage_Core_Model_App::ADMIN_STORE_ID);

$eavConfig = Mage::getSingleton('eav/config');
$attribute = array(
    'label'        => 'Facebook UID',
    'visible'      => true,
    'unique'       => true,
    'required'     => false,
    'type'         => 'varchar',
    'input'        => 'text',
    'sort_order'    => 65,
    'validate_rules'    => array(
        'max_text_length'   => 30,
        'min_text_length'   => 1
    ),
    'used_in_forms' => array('adminhtml_customer'),
);

$installer->addAttribute('customer','facebook_uid', $attribute);

$attributes  = array('facebook_uid' => $attribute);

foreach ($attributes as $attributeCode => $data) {
    $attribute = $eavConfig->getAttribute('customer', $attributeCode);
    $attribute->setWebsite( (($store->getWebsite())?$store->getWebsite():0));
    $attribute->addData($data);
    $attribute->save();
}

$installer->endSetup();