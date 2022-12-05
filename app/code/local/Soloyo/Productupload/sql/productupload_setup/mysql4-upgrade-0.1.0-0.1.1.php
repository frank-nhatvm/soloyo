<?php
$installer = $this;
$resource = Mage::getSingleton('core/resource');
$installer->startSetup();

$installer->run("

DROP TABLE IF EXISTS {$this->getTable('designer')};
CREATE TABLE {$this->getTable('designer')} (
`designer_id` int(11) unsigned NOT NULL auto_increment,
`balance` decimal (10,2)  NOT NULL default '0',
`user_id` int(11),
`email` varchar(255) NOT NULL default '',
`bank_owner_name` varchar(255) NOT NULL default '',
`bank_account_number` varchar(255) NOT NULL default '',
`bank_name` varchar(255) NOT NULL default '',
`bank_area` varchar(255) NOT NULL default '',
`status` smallint(6) NOT NULL default '0',
`created_time` datetime NULL,
`update_time` datetime NULL,
  PRIMARY KEY (`designer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS {$this->getTable('designertransaction')};
CREATE TABLE {$this->getTable('designertransaction')} (
`designer_transaction_id` int(11) unsigned NOT NULL auto_increment,
`designer_id` int(11) ,
`amount` decimal (10,2) ,
`image_transaction` varchar(255) NOT NULL default '',
`status` smallint(6) NOT NULL default '0',
`admin_comment` text NOT NULL default '',
`designer_comment` text NOT NULL default '',
`created_time` datetime NULL,
`update_time` datetime NULL,
  PRIMARY KEY (`designer_transaction_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


");

$installer->endSetup();