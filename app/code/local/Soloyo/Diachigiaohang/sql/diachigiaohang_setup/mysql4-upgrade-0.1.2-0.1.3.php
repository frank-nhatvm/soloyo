<?php

$installer = $this;

$installer->startSetup();

$setup = $this;

$entityTypeId = $setup->getEntityTypeId('customer_address');
$attributeSetId = $setup->getDefaultAttributeSetId($entityTypeId);
$attributeGroupId = $setup->getDefaultAttributeGroupId($entityTypeId, $attributeSetId);

//this is for creating a new attribute for customer address entity
$setup->addAttribute("customer_address", "xaphuonglabel", array(
    'type' => 'varchar',
    'input' => 'text',
    'label' => 'Xa phuong Label',
    'global' => 1,
    'visible' => 1,
    'required' => 0,
    'user_defined' => 1,
    'visible_on_front' => 1
));

$attribute = Mage::getSingleton("eav/config")->getAttribute("customer_address", "xaphuonglabel");

$setup->addAttributeToGroup(
    $entityTypeId, $attributeSetId, $attributeGroupId, 'xaphuong', '1999'  //sort_order
);

$used_in_forms = array();

$used_in_forms[]="adminhtml_customer";
$used_in_forms[]="adminhtml_customer_address";
$used_in_forms[]="adminhtml_checkout";
$used_in_forms[]="checkout_register";


$used_in_forms[] = "customer_address_edit";
$used_in_forms[]="customer_account_create";
$used_in_forms[] = "customer_register_address";

$attribute->setData("used_in_forms", $used_in_forms)
    ->setData("is_used_for_customer_segment", true)
    ->setData("is_system", 0)
    ->setData("is_user_defined", 1)
    ->setData("is_visible", 1)
    ->setData("sort_order", 100)
;
$attribute->save();


/**
 * Adding Extra Column to sales_flat_quote_address
 * to store the delivery instruction field
 */
$sales_quote_address = $installer->getTable('sales/quote_address');
$installer->getConnection()
    ->addColumn($sales_quote_address, 'xaphuonglabel', array(
        'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
        'comment' => 'New Xa phuong label Field Added'
    ));

/**
 * Adding Extra Column to sales_flat_order_address
 * to store the delivery instruction field
 */
$sales_order_address = $installer->getTable('sales/order_address');
$installer->getConnection()
    ->addColumn($sales_order_address, 'xaphuonglabel', array(
        'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
        'comment' => 'New xa phuong label Field Added'
    ));


$config = Mage::getModel('core/config');

//append delivery instruction to address templates in system configuration
$html = Mage::getConfig()->getNode('default/customer/address_templates/html');
$html .= '{{depend xaphuonglabel}}<br/>xaphuonglabel:{{var xaphuonglabel}} {{/depend}}';
$config->saveConfig('customer/address_templates/html', $html);

$text = Mage::getConfig()->getNode('default/customer/address_templates/text');
$text .= '{{depend xaphuonglabel}}<br/>xaphuonglabel:{{var xaphuonglabel}} {{/depend}}';
$config->saveConfig('customer/address_templates/text', $text);

$oneline = Mage::getConfig()->getNode('default/customer/address_templates/oneline');
$oneline .= '{{depend xaphuonglabel}}<br/>xaphuonglabel:{{var xaphuonglabel}} {{/depend}}';
$config->saveConfig('customer/address_templates/oneline', $oneline);

$pdf = Mage::getConfig()->getNode('default/customer/address_templates/pdf');
$pdf .= '{{depend xaphuonglabel}}<br/>xaphuonglabel:{{var xaphuonglabel}} {{/depend}}';
$config->saveConfig('customer/address_templates/pdf', $pdf);

$js_template = Mage::getConfig()->getNode('default/customer/address_templates/js_template');
$js_template .= '{{depend xaphuonglabel}}<br/>xaphuonglabel:{{var xaphuonglabel}} {{/depend}}';
$config->saveConfig('customer/address_templates/js_template', $js_template);
$installer->endSetup();