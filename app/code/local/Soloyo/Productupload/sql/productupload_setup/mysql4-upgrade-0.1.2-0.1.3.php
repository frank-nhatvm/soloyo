<?php
$installer = $this;
$resource = Mage::getSingleton('core/resource');
$installer->startSetup();


$installer->getConnection()->addColumn($resource->getTableName('productupload/productupload'), 'qty_sale', array(
    'type' => Varien_Db_Ddl_Table::TYPE_INTEGER,
    'length' => 12,
    'default' => 0,
    'comment' => 'Quantity sale'
));


$installer->endSetup();