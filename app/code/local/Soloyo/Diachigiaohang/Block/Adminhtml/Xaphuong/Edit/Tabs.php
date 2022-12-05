<?php

class Soloyo_Diachigiaohang_Block_Adminhtml_Xaphuong_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('xaphuong_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('diachigiaohang')->__('Item Information'));
    }
    
    /**
     * prepare before render block to html
     *
     * @return Soloyo_Diachigiaohang_Block_Adminhtml_Diachigiaohang_Edit_Tabs
     */
    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label'     => Mage::helper('diachigiaohang')->__('Item Information'),
            'title'     => Mage::helper('diachigiaohang')->__('Item Information'),
            'content'   => $this->getLayout()
                                ->createBlock('diachigiaohang/adminhtml_xaphuong_edit_tab_form')
                                ->toHtml(),
        ));
        return parent::_beforeToHtml();
    }
}