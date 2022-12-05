<?php
$installer = $this;
/* @var $installer Mage_Customer_Model_Entity_Setup */
$installer->startSetup();
$resource = Mage::getSingleton('core/resource');
$installer->getConnection()->addColumn($resource->getTableName('campaign/affplayer'), 'campaign_id', 'int');
$installer->run("

DROP TABLE IF EXISTS {$this->getTable('campaign')};

CREATE TABLE {$this->getTable('campaign')} (
  `campaign_id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `banner` varchar(255) NOT NULL default '',
  `adward_text` text NOT NULL default '',
  `adward_image` varchar(255) NOT NULL default '',
  `rule_win` text NOT NULL default '',
  `rule_win_other` text NOT NULL default '',
  `other_title` text NOT NULL default '',
  `other_text` text NOT NULL default '',
  `num_player` int(11),
  `url` varchar(255) NOT NULL default '',
  `image_share` varchar(255) NOT NULL default '',
  `status` smallint(6) NOT NULL default '0',
  `start_time` datetime NULL,
  `end_time` datetime NULL,
  `update_time` datetime NULL,
  PRIMARY KEY (`campaign_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");

$installer->endSetup();