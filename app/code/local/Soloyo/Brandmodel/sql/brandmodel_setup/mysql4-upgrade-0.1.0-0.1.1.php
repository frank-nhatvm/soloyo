<?php
$installer = $this;

$installer->startSetup();

$installer->run("
DROP TABLE IF EXISTS {$this->getTable('requestbrand')};

CREATE TABLE {$this->getTable('requestbrand')} (
  `requestbrand_id` int(11) unsigned NOT NULL auto_increment,
  `user_id` int(11),
  `email` varchar(255) ,
  `phone` varchar(255) ,
  `requirement` text not NULL DEFAULT '',
  `status` smallint(6) NOT NULL default '0',
  `created_time` datetime NULL,
  `update_time` datetime NULL,
  PRIMARY KEY (`requestbrand_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->endSetup();