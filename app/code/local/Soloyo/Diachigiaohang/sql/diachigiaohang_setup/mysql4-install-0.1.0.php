<?php

$installer = $this;

$installer->startSetup();


$installer->run("

DROP TABLE IF EXISTS {$this->getTable('tinhthanh')};
CREATE TABLE {$this->getTable('tinhthanh')} (
  `tinhthanh_id` int(11) unsigned NOT NULL auto_increment,
  `ten_tinhthanh` varchar(255) NOT NULL default '',
  `giavanchuyen` decimal(12,4),
  `can_shipping` smallint(6) NOT NULL default '1',
  `status` smallint(6) NOT NULL default '0',
  `use_ship_quanhuyen` smallint(6) NOT NULL default '0',
  `created_time` datetime NULL,
  `update_time` datetime NULL,
  PRIMARY KEY (`tinhthanh_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS {$this->getTable('quanhuyen')};
CREATE TABLE {$this->getTable('quanhuyen')} (
  `quanhuyen_id` int(11) unsigned NOT NULL auto_increment,
   `tinhthanh_id` int(11),
  `ten_quanhuyen` varchar(255) NOT NULL default '',
  `giavanchuyen` decimal(12,4),
  `can_shipping` smallint(6) NOT NULL default '1',
  `status` smallint(6) NOT NULL default '0',
    `use_ship_xaphuong` smallint(6) NOT NULL default '0',
  `created_time` datetime NULL,
  `update_time` datetime NULL,
  PRIMARY KEY (`quanhuyen_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS {$this->getTable('xaphuong')};
CREATE TABLE {$this->getTable('xaphuong')} (
  `xaphuong_id` int(11) unsigned NOT NULL auto_increment,
   `quanhuyen_id` int(11),
  `ten_xaphuong` varchar(255) NOT NULL default '',
  `giavanchuyen` decimal(12,4),
  `can_shipping` smallint(6) NOT NULL default '1',
  `status` smallint(6) NOT NULL default '0',
  `created_time` datetime NULL,
  `update_time` datetime NULL,
  PRIMARY KEY (`xaphuong_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");

$installer->endSetup();

