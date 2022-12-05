<?php
$installer = $this;
$resource = Mage::getSingleton('core/resource');
$installer->startSetup();

$installer->run("
	ALTER TABLE {$resource->getTableName('casedesign/caseorderitem')} ADD `qty_ordered` int(11) default NULL;
	");
$installer->run("
ALTER TABLE {$resource->getTableName('casedesign/caseorderitem')} ADD `options` text default NULL;
	");

$installer->endSetup();