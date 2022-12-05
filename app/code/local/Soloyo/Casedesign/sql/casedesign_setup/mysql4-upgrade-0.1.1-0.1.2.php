<?php
$installer = $this;
$resource = Mage::getSingleton('core/resource');
$installer->startSetup();

$installer->run("
  
DROP TABLE IF EXISTS {$this->getTable('caseorder_item')};
CREATE TABLE {$this->getTable('caseorder_item')} (
  `caseorder_item_id` int(11) unsigned NOT NULL auto_increment,
`caseorder_id` int(11),
`product_id` int(11),
`casedesign_id` int(11),
`content_design` text NOT NULL default '',
`thumb_image` varchar(255) ,
`design_image` varchar(255) ,
`status` smallint(6) NOT NULL default '0',
  `created_time` datetime NULL,
  `update_time` datetime NULL,
  PRIMARY KEY (`caseorder_item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
 
ALTER TABLE `{$this->getTable('caseorder')}` DROP casedesign_id ;
ALTER TABLE `{$this->getTable('caseorder')}` DROP content_design ;
  
    ");

$installer->endSetup();