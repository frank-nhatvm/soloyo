<?php
$installer = $this;
$resource = Mage::getSingleton('core/resource');
$installer->startSetup();

$installer->run("
	ALTER TABLE {$resource->getTableName('casedesign/caseorder')} ADD `printer_id` int(11) default NULL;
	");
$installer->run("
ALTER TABLE {$resource->getTableName('casedesign/caseorder')} ADD `is_customer_design` smallint(6) default '0';
	");

$installer->endSetup();