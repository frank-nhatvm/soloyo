<?php

class Soloyo_Campaign_Block_Adminhtml_Aff_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('affGrid');
        $this->setDefaultSort('player_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }
    
    /**
     * prepare collection for block to display
     *
     * @return Soloyo_Campaign_Block_Adminhtml_Campaign_Grid
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('campaign/affplayer')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
    
    /**
     * prepare columns for this grid
     *
     * @return Soloyo_Campaign_Block_Adminhtml_Campaign_Grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn('player_id', array(
            'header'    => Mage::helper('campaign')->__('ID'),
            'align'     =>'right',
            'width'     => '50px',
            'index'     => 'player_id',
        ));

        $this->addColumn('name', array(
            'header'    => Mage::helper('campaign')->__('name'),
            'align'     =>'left',
            'index'     => 'name',
        ));

        $this->addColumn('campaign_id', array(
            'header'    => Mage::helper('campaign')->__('Campaign ID'),
            'align'     =>'left',
            'index'     => 'campaign_id',
        ));

        $this->addColumn('email', array(
            'header'    => Mage::helper('campaign')->__('email'),
            'align'     =>'left',
            'index'     => 'email',
        ));
        $this->addColumn('face_id', array(
            'header'    => Mage::helper('campaign')->__('face_id'),
            'align'     =>'left',
            'index'     => 'face_id',
        ));
        $this->addColumn('code', array(
            'header'    => Mage::helper('campaign')->__('code'),
            'align'     =>'left',
            'index'     => 'code',
        ));

        $this->addColumn('status', array(
            'header'    => Mage::helper('campaign')->__('Status'),
            'align'     => 'left',
            'width'     => '80px',
            'index'     => 'status',
            'type'        => 'options',
            'options'     => array(
                1 => 'Register',
                2 => 'Shared',
            ),
        ));

        $this->addColumn('action',
            array(
                'header'    =>    Mage::helper('campaign')->__('Action'),
                'width'        => '100',
                'type'        => 'action',
                'getter'    => 'getId',
                'actions'    => array(
                    array(
                        'caption'    => Mage::helper('campaign')->__('Edit'),
                        'url'        => array('base'=> '*/*/edit'),
                        'field'        => 'id'
                    )),
                'filter'    => false,
                'sortable'    => false,
                'index'        => 'stores',
                'is_system'    => true,
        ));

        $this->addExportType('*/*/exportCsv', Mage::helper('campaign')->__('CSV'));
        $this->addExportType('*/*/exportXml', Mage::helper('campaign')->__('XML'));

        return parent::_prepareColumns();
    }
    
    /**
     * prepare mass action for this grid
     *
     * @return Soloyo_Campaign_Block_Adminhtml_Campaign_Grid
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('player_id');
        $this->getMassactionBlock()->setFormFieldName('campaign');

        $this->getMassactionBlock()->addItem('delete', array(
            'label'        => Mage::helper('campaign')->__('Delete'),
            'url'        => $this->getUrl('*/*/massDelete'),
            'confirm'    => Mage::helper('campaign')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('campaign/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
            'label'=> Mage::helper('campaign')->__('Change status'),
            'url'    => $this->getUrl('*/*/massStatus', array('_current'=>true)),
            'additional' => array(
                'visibility' => array(
                    'name'    => 'status',
                    'type'    => 'select',
                    'class'    => 'required-entry',
                    'label'    => Mage::helper('campaign')->__('Status'),
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