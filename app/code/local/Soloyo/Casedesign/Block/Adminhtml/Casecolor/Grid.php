<?php

class Soloyo_Casedesign_Block_Adminhtml_Casecolor_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('casedesignGrid');
        $this->setDefaultSort('color_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }
    
    /**
     * prepare collection for block to display
     *
     * @return Soloyo_Casedesign_Block_Adminhtml_Casedesign_Grid
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('casedesign/casecolor')->getCollection();
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
        $this->addColumn('color_id', array(
            'header'    => Mage::helper('casedesign')->__('ID'),
            'align'     =>'right',
            'width'     => '50px',
            'index'     => 'color_id',
        ));

        $this->addColumn('name', array(
            'header'    => Mage::helper('casedesign')->__('Name'),
            'align'     =>'left',
            'index'     => 'name',
        ));

        $this->addColumn('color_code', array(
            'header'    => Mage::helper('casedesign')->__('Color'),
            'width'     => '150px',
            'renderer' => 'Soloyo_Casedesign_Block_Adminhtml_Renderer_Color',
            'index'     => 'color_code',
        ));

        $this->addColumn('status', array(
            'header'    => Mage::helper('casedesign')->__('Status'),
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
                'header'    =>    Mage::helper('casedesign')->__('Action'),
                'width'        => '100',
                'type'        => 'action',
                'getter'    => 'getId',
                'actions'    => array(
                    array(
                        'caption'    => Mage::helper('casedesign')->__('Edit'),
                        'url'        => array('base'=> '*/*/edit'),
                        'field'        => 'id'
                    )),
                'filter'    => false,
                'sortable'    => false,
                'index'        => 'stores',
                'is_system'    => true,
        ));

        $this->addExportType('*/*/exportCsv', Mage::helper('casedesign')->__('CSV'));
        $this->addExportType('*/*/exportXml', Mage::helper('casedesign')->__('XML'));

        return parent::_prepareColumns();
    }
    
    /**
     * prepare mass action for this grid
     *
     * @return Soloyo_Casedesign_Block_Adminhtml_Casedesign_Grid
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('color_id');
        $this->getMassactionBlock()->setFormFieldName('casecolor');

        $this->getMassactionBlock()->addItem('delete', array(
            'label'        => Mage::helper('casedesign')->__('Delete'),
            'url'        => $this->getUrl('*/*/massDelete'),
            'confirm'    => Mage::helper('casedesign')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('casedesign/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
            'label'=> Mage::helper('casedesign')->__('Change status'),
            'url'    => $this->getUrl('*/*/massStatus', array('_current'=>true)),
            'additional' => array(
                'visibility' => array(
                    'name'    => 'status',
                    'type'    => 'select',
                    'class'    => 'required-entry',
                    'label'    => Mage::helper('casedesign')->__('Status'),
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