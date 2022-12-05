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
 * @package     Magestore_Menutop
 * @copyright   Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license     http://www.magestore.com/license-agreement.html
 */

/**
 * Menutop Adminhtml Block
 * 
 * @category    Magestore
 * @package     Magestore_Menutop
 * @author      Magestore Developer
 */
class Soloyo_Menutop_Block_Adminhtml_Menutop extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_menutop';
        $this->_blockGroup = 'menutop';
        $this->_headerText = Mage::helper('menutop')->__('Item Manager');
        $this->_addButtonLabel = Mage::helper('menutop')->__('Add Item');
        parent::__construct();
    }
}