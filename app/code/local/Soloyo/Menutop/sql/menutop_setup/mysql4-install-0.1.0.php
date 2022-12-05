<?php
$installer = $this;

$installer->startSetup();

/**
 * create menutop table
 */
$installer->run("

DROP TABLE IF EXISTS {$this->getTable('menutop')};

CREATE TABLE {$this->getTable('menutop')} (
  `menutop_id` int(11) unsigned NOT NULL auto_increment,
  `menutop_cat_id` int(11), 
  `menutop_position` int(11),
  `menu_name` varchar(255) NOT NULL default '',
  `menu_url` text NOT NULL default '',
  `status` smallint(6) NOT NULL default '0',
  `created_time` datetime NULL,
  `update_time` datetime NULL,
  PRIMARY KEY (`menutop_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");

$installer->endSetup();

