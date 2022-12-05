<?php
$installer = $this;
$resource = Mage::getSingleton('core/resource');
$installer->startSetup();

$installer->getConnection()->addColumn($resource->getTableName('sales/quote_item'), 'casedesign_image', 'text');
$installer->getConnection()->addColumn($resource->getTableName('sales/quote_item'), 'casedesign_thumb', 'text');

$installer->getConnection()->addColumn($resource->getTableName('sales/order_item'), 'casedesign_image', 'text');
$installer->getConnection()->addColumn($resource->getTableName('sales/order_item'), 'casedesign_thumb', 'text');

$installer->endSetup();