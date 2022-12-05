<?php
$installer = $this;
$resource = Mage::getSingleton('core/resource');
$installer->startSetup();
$installer->run("
ALTER TABLE {$resource->getTableName('productupload/designer')} ADD `total_balance` decimal (10,2)  NOT NULL default '0';
");
$installer->endSetup();