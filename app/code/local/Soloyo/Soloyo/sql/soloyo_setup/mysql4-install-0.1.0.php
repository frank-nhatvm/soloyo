<?php
$installer = $this;

$installer->startSetup();


$installer->run("

DROP TABLE IF EXISTS {$this->getTable('homebanner')};
CREATE TABLE {$this->getTable('homebanner')} (
  `banner_id` int(11) unsigned NOT NULL auto_increment,
  `position` int(11) ,
  `name` varchar(255) NOT NULL default '',
  `image` varchar(255) NOT NULL default '',
  `url` varchar(255) NOT NULL default '',
   `status` smallint(6) NOT NULL default '0',
  `created_time` datetime NULL,
  `update_time` datetime NULL,
  PRIMARY KEY (`banner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS {$this->getTable('homecate')};
CREATE TABLE {$this->getTable('homecate')} (
  `home_cate_id` int(11) unsigned NOT NULL auto_increment,
  `cate_id` int(11) ,
  `position` int(11) ,
  `name` varchar(255) NOT NULL default '',
  `image` varchar(255) NOT NULL default '',
  `url` varchar(255) NOT NULL default '',
   `status` smallint(6) NOT NULL default '0',
  `created_time` datetime NULL,
  `update_time` datetime NULL,
  PRIMARY KEY (`home_cate_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS {$this->getTable('homeproducts')};
CREATE TABLE {$this->getTable('homeproducts')} (
  `homeproducts_id` int(11) unsigned NOT NULL auto_increment,
  `cate_id` int(11) ,
  `position` int(11) ,
  `name` varchar(255) NOT NULL default '',
  `image` varchar(255) NOT NULL default '',
  `url` varchar(255) NOT NULL default '',
   `type_show` smallint(6) NOT NULL default '0',
   `status` smallint(6) NOT NULL default '0',
  `created_time` datetime NULL,
  `update_time` datetime NULL,
  PRIMARY KEY (`homeproducts_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS {$this->getTable('homeitem')};
CREATE TABLE {$this->getTable('homeitem')} (
  `homeitem_id` int(11) unsigned NOT NULL auto_increment,
  `cate_id` int(11) ,
  `product_id` int(11) ,
  `parent_id` int(11) ,
  `position` int(11) ,
  `name` varchar(255) NOT NULL default '',
  `image` varchar(255) NOT NULL default '',
  `url` varchar(255) NOT NULL default '',
   `type` smallint(6) NOT NULL default '0',
   `status` smallint(6) NOT NULL default '0',
  `created_time` datetime NULL,
  `update_time` datetime NULL,
  PRIMARY KEY (`homeitem_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



");

$installer->endSetup();

