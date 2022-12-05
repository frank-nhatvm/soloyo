<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 8/18/18
 * Time: 9:12 AM
 */

class Soloyo_Brandmodel_Block_Adminhtml_Brandmobile_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('brandmobileGrid');
        $this->setDefaultSort('brandmobile_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    /**
     * prepare collection for block to display
     *
     * @return Soloyo_Brandmodel_Block_Adminhtml_Brandmodel_Grid
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('brandmodel/brandmobile')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare columns for this grid
     *
     * @return Soloyo_Brandmodel_Block_Adminhtml_Brandmodel_Grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn('brandmobile_id', array(
            'header'    => Mage::helper('brandmodel')->__('ID'),
            'align'     =>'right',
            'width'     => '50px',
            'index'     => 'brandmobile_id',
        ));

        $this->addColumn('brand_name', array(
            'header'    => Mage::helper('brandmodel')->__('Name'),
            'align'     =>'left',
            'index'     => 'brand_name',
        ));


        $this->addColumn('status', array(
            'header'    => Mage::helper('brandmodel')->__('Status'),
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
     * @return Soloyo_Brandmodel_Block_Adminhtml_Brandmodel_Grid
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('brandmobile_id');
        $this->getMassactionBlock()->setFormFieldName('brandmobile');

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