<?php

class Soloyo_Printer_Block_Adminhtml_Printer_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('printerGrid');
        $this->setDefaultSort('printer_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }
    
    /**
     * prepare collection for block to display
     *
     * @return Soloyo_Printer_Block_Adminhtml_Printer_Grid
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('printer/printer')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
    
    /**
     * prepare columns for this grid
     *
     * @return Soloyo_Printer_Block_Adminhtml_Printer_Grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn('printer_id', array(
            'header'    => Mage::helper('printer')->__('ID'),
            'align'     =>'right',
            'width'     => '50px',
            'index'     => 'printer_id',
        ));

        $this->addColumn('email', array(
            'header'    => Mage::helper('printer')->__('Email'),
            'align'     =>'left',
            'index'     => 'email',
        ));
        $this->addColumn('name', array(
            'header'    => Mage::helper('printer')->__('Name'),
            'align'     =>'left',
            'index'     => 'name',
        ));
        $this->addColumn('address', array(
            'header'    => Mage::helper('printer')->__('Address'),
            'align'     =>'left',
            'index'     => 'address',
        ));

        $this->addColumn('status', array(
            'header'    => Mage::helper('printer')->__('Status'),
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
                'header'    =>    Mage::helper('printer')->__('Action'),
                'width'        => '100',
                'type'        => 'action',
                'getter'    => 'getId',
                'actions'    => array(
                    array(
                        'caption'    => Mage::helper('printer')->__('Edit'),
                        'url'        => array('base'=> '*/*/edit'),
                        'field'        => 'id'
                    )),
                'filter'    => false,
                'sortable'    => false,
                'index'        => 'stores',
                'is_system'    => true,
        ));

        $this->addExportType('*/*/exportCsv', Mage::helper('printer')->__('CSV'));
        $this->addExportType('*/*/exportXml', Mage::helper('printer')->__('XML'));

        return parent::_prepareColumns();
    }
    
    /**
     * prepare mass action for this grid
     *
     * @return Soloyo_Printer_Block_Adminhtml_Printer_Grid
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('printer_id');
        $this->getMassactionBlock()->setFormFieldName('printer');

        $this->getMassactionBlock()->addItem('delete', array(
            'label'        => Mage::helper('printer')->__('Delete'),
            'url'        => $this->getUrl('*/*/massDelete'),
            'confirm'    => Mage::helper('printer')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('printer/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
            'label'=> Mage::helper('printer')->__('Change status'),
            'url'    => $this->getUrl('*/*/massStatus', array('_current'=>true)),
            'additional' => array(
                'visibility' => array(
                    'name'    => 'status',
                    'type'    => 'select',
                    'class'    => 'required-entry',
                    'label'    => Mage::helper('printer')->__('Status'),
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