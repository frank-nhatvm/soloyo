<?php
$installer = $this;
$resource = Mage::getSingleton('core/resource');
$installer->startSetup();
$installer->run("

DROP TABLE IF EXISTS {$this->getTable('requestproduct')};

CREATE TABLE {$this->getTable('requestproduct')} (
  `requestproduct_id` int(11) unsigned NOT NULL auto_increment,
  `designer_id` int(11) ,
  `product_id` int(11) ,
    `user_id` int(11),
  `email` varchar(255) ,
  `phone` varchar(255) ,
  `requirement` text not NULL DEFAULT '',
  `status` smallint(6) NOT NULL default '0',
  `is_send` smallint(6) NOT NULL default '0',
  `created_time` datetime NULL,
  `update_time` datetime NULL,
  PRIMARY KEY (`requestproduct_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->endSetup();