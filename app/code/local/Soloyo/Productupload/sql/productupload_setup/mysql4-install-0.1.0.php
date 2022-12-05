<?php
$installer = $this;

$installer->startSetup();


$installer->run("

DROP TABLE IF EXISTS {$this->getTable('productupload')};

CREATE TABLE {$this->getTable('productupload')} (
  `productupload_id` int(11) unsigned NOT NULL auto_increment,
  `is_admin` smallint(6) NOT NULL default '0',
  `designer_id` int(11) ,
  `product_id` int(11) ,
  `product_name` varchar(255) NOT NULL default '',
  `image_product` varchar(255) NOT NULL default '',
  `parent_cate_id` int(11) ,
  `cate_id` int(11) ,
  `cate_name` varchar(255) NOT NULL default '',
  `is_new_cate` smallint(6) NOT NULL default '0',
  `image_cate` varchar(255) NOT NULL default '',
  `is_for_sale` smallint(6) NOT NULL default '0',
  `price` decimal (10,2) ,
  `price_for_seller` decimal (10,2) ,
  `description` text NOT NULL default '',
  `short_description` text NOT NULL default '',
  `admin_comment` text NOT NULL default '',
  `product_sku` varchar(255) NOT NULL default '',
  `brand_id` int(11) ,
  `model_id` int(11) ,
  `status` smallint(6) NOT NULL default '0',
  `created_time` datetime NULL,
  `update_time` datetime NULL,
  PRIMARY KEY (`productupload_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS {$this->getTable('imageprint')};
CREATE TABLE {$this->getTable('imageprint')} (
  `imageprint_id` int(11) unsigned NOT NULL auto_increment,
  `product_id` int(11) ,
  `productupload_id` int(11) ,
  `image_product` varchar(255) NOT NULL default '',
  `created_time` datetime NULL,
  `update_time` datetime NULL,
  PRIMARY KEY (`imageprint_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");

$installer->endSetup();

