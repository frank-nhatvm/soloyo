<?php

class Soloyo_Printer_Block_Adminhtml_Printer_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('printer_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('printer')->__('Printer Information'));
    }
    
    /**
     * prepare before render block to html
     *
     * @return Soloyo_Printer_Block_Adminhtml_Printer_Edit_Tabs
     */
    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label'     => Mage::helper('printer')->__('Item Information'),
            'title'     => Mage::helper('printer')->__('Item Information'),
            'content'   => $this->getLayout()
                                ->createBlock('printer/adminhtml_printer_edit_tab_form')
                                ->toHtml(),
        ));
        return parent::_beforeToHtml();
    }
}