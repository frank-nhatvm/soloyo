<?php
/**
 * Magestore
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Magestore.com license that is
 * available through the world-wide-web at this URL:
 * http://www.magestore.com/license-agreement.html
 * 
 * DISCLAIMER
 * 
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 * 
 * @category    Magestore
 * @package     Magestore_Brandmodel
 * @copyright   Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license     http://www.magestore.com/license-agreement.html
 */

/** @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

/**
 * create brandmodel table
 */
$installer->run("

DROP TABLE IF EXISTS {$this->getTable('brandmobile')};

CREATE TABLE {$this->getTable('brandmobile')} (
`brandmobile_id` int(11) unsigned NOT NULL auto_increment,
  `status` smallint(6) NOT NULL default '0',
  `brand_name` varchar(255) NOT NULL default '',
  `brand_attribute_id` int(11),
  `created_time` datetime NULL,
  `update_time` datetime NULL,
  PRIMARY KEY (`brandmobile_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS {$this->getTable('modelmobile')};
CREATE TABLE {$this->getTable('modelmobile')} (
`modelmobile_id` int(11) unsigned NOT NULL auto_increment,
  `status` smallint(6) NOT NULL default '0',
  `brandmobile_id` int(11),
  `brand_model_attribute_id` int(11),
  `model_name` varchar(255) NOT NULL default '',
  `created_time` datetime NULL,
  `update_time` datetime NULL,
  PRIMARY KEY (`modelmobile_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");



$installer->endSetup();

