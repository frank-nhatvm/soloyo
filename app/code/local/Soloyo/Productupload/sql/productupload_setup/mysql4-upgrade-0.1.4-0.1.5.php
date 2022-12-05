<?php
$installer = $this;
$resource = Mage::getSingleton('core/resource');
$installer->startSetup();
$installer->run("
ALTER TABLE {$resource->getTableName('productupload/productupload')} ADD `url_key` varchar(255) NOT NULL default '';
ALTER TABLE {$resource->getTableName('productupload/productupload')} ADD `image_print` varchar(255) NOT NULL default '';

");

$installer->endSetup();