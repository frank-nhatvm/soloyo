<?php

class Soloyo_Brandmodel_Block_Adminhtml_Requestbrand_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('requestbrandGrid');
        $this->setDefaultSort('requestbrand_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }
    

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('brandmodel/requestbrand')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
    
    /**
     * prepare columns for this grid
     *
     * @return Soloyo_Casedesign_Block_Adminhtml_Casedesign_Grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn('requestbrand_id', array(
            'header'    => Mage::helper('brandmodel')->__('ID'),
            'align'     =>'right',
            'width'     => '50px',
            'index'     => 'requestbrand_id',
        ));

        $this->addColumn('email', array(
            'header'    => Mage::helper('brandmodel')->__('Email'),
            'align'     =>'left',
            'width'     => '150px',
            'index'     => 'email',
        ));

        $this->addColumn('phone', array(
            'header'    => Mage::helper('brandmodel')->__('Phone'),
            'width'     => '150px',
            'index'     => 'phone',
        ));

        $this->addColumn('requirement', array(
            'header'    => Mage::helper('brandmodel')->__('Requirement'),

            'index'     => 'requirement',
        ));

        $this->addColumn('status', array(
            'header'    => Mage::helper('brandmodel')->__('Status'),
            'align'     => 'left',
            'width'     => '80px',
            'index'     => 'status',
            'type'        => 'options',
            'options'     => array(
                0 => 'Pending',
                1 => 'Updated',
                2 => 'Cancel',
            ),
        ));

        $this->addColumn('action',
            array(
                'header'    =>    Mage::helper('brandmodel')->__('Action'),
                'width'        => '100',
                'type'        => 'action',
                'getter'    => 'getId',
                'actions'    => array(
                    array(
                        'caption'    => Mage::helper('brandmodel')->__('Edit'),
                        'url'        => array('base'=> '*/*/edit'),
                        'field'        => 'id'
                    )),
                'filter'    => false,
                'sortable'    => false,
                'index'        => 'stores',
                'is_system'    => true,
        ));

        $this->addExportType('*/*/exportCsv', Mage::helper('brandmodel')->__('CSV'));
        $this->addExportType('*/*/exportXml', Mage::helper('brandmodel')->__('XML'));

        return parent::_prepareColumns();
    }
    
    /**
     * prepare mass action for this grid
     *
     * @return Soloyo_Casedesign_Block_Adminhtml_Casedesign_Grid
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('requestbrand_id');
        $this->getMassactionBlock()->setFormFieldName('requestbrand');

        $this->getMassactionBlock()->addItem('delete', array(
            'label'        => Mage::helper('brandmodel')->__('Delete'),
            'url'        => $this->getUrl('*/*/massDelete'),
            'confirm'    => Mage::helper('brandmodel')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('brandmodel/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
            'label'=> Mage::helper('brandmodel')->__('Change status'),
            'url'    => $this->getUrl('*/*/massStatus', array('_current'=>true)),
            'additional' => array(
                'visibility' => array(
                    'name'    => 'status',
                    'type'    => 'select',
                    'class'    => 'required-entry',
                    'label'    => Mage::helper('brandmodel')->__('Status'),
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