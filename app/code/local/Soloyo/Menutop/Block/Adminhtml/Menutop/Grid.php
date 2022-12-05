<?php

class Soloyo_Menutop_Block_Adminhtml_Menutop_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('menutopGrid');
        $this->setDefaultSort('menutop_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }
    

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('menutop/menutop')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
    
    /**
     * prepare columns for this grid
     *
     * @return Soloyo_Menutop_Block_Adminhtml_Menutop_Grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn('menutop_id', array(
            'header'    => Mage::helper('menutop')->__('ID'),
            'align'     =>'right',
            'width'     => '50px',
            'index'     => 'menutop_id',
        ));

        $this->addColumn('menu_name', array(
            'header'    => Mage::helper('menutop')->__('Name'),
            'align'     =>'left',
            'index'     => 'menu_name',
        ));

        $this->addColumn('menu_url', array(
            'header'    => Mage::helper('menutop')->__('Url'),
            'width'     => '150px',
            'index'     => 'menu_url',
        ));

        $this->addColumn('status', array(
            'header'    => Mage::helper('menutop')->__('Status'),
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
                'header'    =>    Mage::helper('menutop')->__('Action'),
                'width'        => '100',
                'type'        => 'action',
                'getter'    => 'getId',
                'actions'    => array(
                    array(
                        'caption'    => Mage::helper('menutop')->__('Edit'),
                        'url'        => array('base'=> '*/*/edit'),
                        'field'        => 'id'
                    )),
                'filter'    => false,
                'sortable'    => false,
                'index'        => 'stores',
                'is_system'    => true,
        ));

        $this->addExportType('*/*/exportCsv', Mage::helper('menutop')->__('CSV'));
        $this->addExportType('*/*/exportXml', Mage::helper('menutop')->__('XML'));

        return parent::_prepareColumns();
    }
    
    /**
     * prepare mass action for this grid
     *
     * @return Soloyo_Menutop_Block_Adminhtml_Menutop_Grid
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('menutop_id');
        $this->getMassactionBlock()->setFormFieldName('menutop');

        $this->getMassactionBlock()->addItem('delete', array(
            'label'        => Mage::helper('menutop')->__('Delete'),
            'url'        => $this->getUrl('*/*/massDelete'),
            'confirm'    => Mage::helper('menutop')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('menutop/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
            'label'=> Mage::helper('menutop')->__('Change status'),
            'url'    => $this->getUrl('*/*/massStatus', array('_current'=>true)),
            'additional' => array(
                'visibility' => array(
                    'name'    => 'status',
                    'type'    => 'select',
                    'class'    => 'required-entry',
                    'label'    => Mage::helper('menutop')->__('Status'),
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