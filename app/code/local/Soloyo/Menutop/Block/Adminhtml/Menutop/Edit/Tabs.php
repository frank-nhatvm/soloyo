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
 * Menutop Edit Tabs Block
 * 
 * @category    Magestore
 * @package     Magestore_Menutop
 * @author      Magestore Developer
 */
class Soloyo_Menutop_Block_Adminhtml_Menutop_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('menutop_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('menutop')->__('Item Information'));
    }
    
    /**
     * prepare before render block to html
     *
     * @return Soloyo_Menutop_Block_Adminhtml_Menutop_Edit_Tabs
     */
    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label'     => Mage::helper('menutop')->__('Item Information'),
            'title'     => Mage::helper('menutop')->__('Item Information'),
            'content'   => $this->getLayout()
                                ->createBlock('menutop/adminhtml_menutop_edit_tab_form')
                                ->toHtml(),
        ));
        return parent::_beforeToHtml();
    }
}