<?php

class Soloyo_Diachigiaohang_Block_Adminhtml_Xaphuong_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('xaphuongGrid');
        $this->setDefaultSort('xaphuong_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }
    
    /**
     * prepare collection for block to display
     *
     * @return Soloyo_Diachigiaohang_Block_Adminhtml_Diachigiaohang_Grid
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('diachigiaohang/xaphuong')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
    
    /**
     * prepare columns for this grid
     *
     * @return Soloyo_Diachigiaohang_Block_Adminhtml_Diachigiaohang_Grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn('xaphuong_id', array(
            'header'    => Mage::helper('diachigiaohang')->__('ID'),
            'align'     =>'right',
            'width'     => '50px',
            'index'     => 'xaphuong_id',
        ));

        $this->addColumn('ten_xaphuong', array(
            'header'    => Mage::helper('diachigiaohang')->__('Name'),
            'align'     =>'left',
            'index'     => 'ten_xaphuong',
        ));

        $this->addColumn('giavanchuyen', array(
            'header'    => Mage::helper('diachigiaohang')->__('Shipping cost'),
            'width'     => '150px',
            'index'     => 'giavanchuyen',
        ));

        $this->addColumn('status', array(
            'header'    => Mage::helper('diachigiaohang')->__('Status'),
            'align'     => 'left',
            'width'     => '80px',
            'index'     => 'status',
            'type'        => 'options',
            'options'     => array(
                1 => 'Enabled',
                2 => 'Disabled',
            ),
        ));

        $this->addColumn('action',
            array(
                'header'    =>    Mage::helper('diachigiaohang')->__('Action'),
                'width'        => '100',
                'type'        => 'action',
                'getter'    => 'getId',
                'actions'    => array(
                    array(
                        'caption'    => Mage::helper('diachigiaohang')->__('Edit'),
                        'url'        => array('base'=> '*/*/edit'),
                        'field'        => 'id'
                    )),
                'filter'    => false,
                'sortable'    => false,
                'index'        => 'stores',
                'is_system'    => true,
        ));

        $this->addExportType('*/*/exportCsv', Mage::helper('diachigiaohang')->__('CSV'));
        $this->addExportType('*/*/exportXml', Mage::helper('diachigiaohang')->__('XML'));

        return parent::_prepareColumns();
    }
    
    /**
     * prepare mass action for this grid
     *
     * @return Soloyo_Diachigiaohang_Block_Adminhtml_Diachigiaohang_Grid
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('xaphuong_id');
        $this->getMassactionBlock()->setFormFieldName('xaphuong');

        $this->getMassactionBlock()->addItem('delete', array(
            'label'        => Mage::helper('diachigiaohang')->__('Delete'),
            'url'        => $this->getUrl('*/*/massDelete'),
            'confirm'    => Mage::helper('diachigiaohang')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('diachigiaohang/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
            'label'=> Mage::helper('diachigiaohang')->__('Change status'),
            'url'    => $this->getUrl('*/*/massStatus', array('_current'=>true)),
            'additional' => array(
                'visibility' => array(
                    'name'    => 'status',
                    'type'    => 'select',
                    'class'    => 'required-entry',
                    'label'    => Mage::helper('diachigiaohang')->__('Status'),
                    'values'=> $statuses
                ))
        ));
        return $this;
    }
    
    /**
     * get url for each row in grid
     *
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
}