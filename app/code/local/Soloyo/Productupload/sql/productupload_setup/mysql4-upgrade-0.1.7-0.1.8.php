<?php
$installer = $this;
$resource = Mage::getSingleton('core/resource');
$installer->startSetup();
$installer->run("

DROP TABLE IF EXISTS {$this->getTable('mockup')};

CREATE TABLE {$this->getTable('mockup')} (
  `mockup_id` int(11) unsigned NOT NULL auto_increment,
  `designer_id` int(11) ,
  `brand_id` int(11) ,
    `model_id` int(11),
  `is_recommend` smallint(6) NOT NULL default '0',
   `mockup_file` varchar(255) NOT NULL default '',
  `status` smallint(6) NOT NULL default '0',
  `created_time` datetime NULL,
  `update_time` datetime NULL,
  PRIMARY KEY (`mockup_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->endSetup();