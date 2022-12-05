<?php
$installer = $this;
$resource = Mage::getSingleton('core/resource');
$installer->startSetup();


$installer->getConnection()->addColumn($resource->getTableName('productupload/productupload'), 'sale_type', array(
    'type' => Varien_Db_Ddl_Table::TYPE_SMALLINT,
    'length' => 6,
    'default' => 1,
    'comment' => 'sale type'
));


$installer->endSetup();