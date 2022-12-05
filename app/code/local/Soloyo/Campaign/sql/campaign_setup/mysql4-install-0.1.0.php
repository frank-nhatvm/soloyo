<?php


/** @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

/**
 * create campaign table
 */
$installer->run("

DROP TABLE IF EXISTS {$this->getTable('campaign')};

CREATE TABLE {$this->getTable('affplayer')} (
  `player_id` int(11) unsigned NOT NULL auto_increment,
  `email` varchar(255) NOT NULL default '',
  `face_id` varchar(255) NOT NULL default '',
  `code` varchar(255) NOT NULL default '',
  `name` varchar(255) NOT NULL default '',
  `status` smallint(6) NOT NULL default '0',
  `created_time` datetime NULL,
  `update_time` datetime NULL,
  PRIMARY KEY (`player_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");

$installer->endSetup();

