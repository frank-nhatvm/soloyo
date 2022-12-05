<?php

$installer = $this;

$installer->startSetup();

/**
 * create casedesign table
 */
$installer->run("

DROP TABLE IF EXISTS {$this->getTable('casedesign')};

CREATE TABLE {$this->getTable('casedesign')} (
  `casedesign_id` int(11) unsigned NOT NULL auto_increment,
  `product_id` int(11),
   `pdi` int(11) NOT NULL default '150',
  `overlay_image` varchar(255) ,
  `background_image` varchar(255),
  `design_area_width` int(11),
  `design_area_height` int(11),
  `design_area_top` int(11),
  `design_area_left` int(11),
  `status` smallint(6) NOT NULL default '0',
  `created_time` datetime NULL,
  `update_time` datetime NULL,
  PRIMARY KEY (`casedesign_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS {$this->getTable('casefont')};
CREATE TABLE {$this->getTable('casefont')} (
  `font_id` int(11) unsigned NOT NULL auto_increment,
`font_url` varchar(255) ,
`name` varchar(255) ,
`status` smallint(6) NOT NULL default '0',
  `created_time` datetime NULL,
  `update_time` datetime NULL,
  PRIMARY KEY (`font_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS {$this->getTable('casecolor')};
CREATE TABLE {$this->getTable('casecolor')} (
  `color_id` int(11) unsigned NOT NULL auto_increment,
`color_code` varchar(255) ,
`name` varchar(255) ,
`status` smallint(6) NOT NULL default '0',
  `created_time` datetime NULL,
  `update_time` datetime NULL,
  PRIMARY KEY (`color_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS {$this->getTable('caseart')};
CREATE TABLE {$this->getTable('caseart')} (
  `art_id` int(11) unsigned NOT NULL auto_increment,
`art_url` varchar(255) ,
`name` varchar(255) ,
`status` smallint(6) NOT NULL default '0',
  `created_time` datetime NULL,
  `update_time` datetime NULL,
  PRIMARY KEY (`art_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS {$this->getTable('caseorder')};
CREATE TABLE {$this->getTable('caseorder')} (
  `caseorder_id` int(11) unsigned NOT NULL auto_increment,
  `casedesign_id` int(11),
`order_id` int(11),
`content_design` text NOT NULL default '',
`thumb_image` varchar(255) ,
`design_image` varchar(255) ,
`status` smallint(6) NOT NULL default '0',
  `created_time` datetime NULL,
  `update_time` datetime NULL,
  PRIMARY KEY (`caseorder_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


");

$installer->endSetup();

