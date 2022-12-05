<?php

class Soloyo_Productupload_Block_Adminhtml_Designertransaction_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('designertransactionGrid');
        $this->setDefaultSort('designer_transaction_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }
    

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('productupload/designertransaction')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
    

    protected function _prepareColumns()
    {
        $this->addColumn('designer_transaction_id', array(
            'header'    => Mage::helper('productupload')->__('ID'),
            'align'     =>'right',
            'width'     => '50px',
            'index'     => 'designer_transaction_id',
        ));

        $this->addColumn('amount', array(
            'header'    => Mage::helper('productupload')->__('Amount'),
            'index'     => 'amount',
        ));

        $this->addColumn('image_transaction', array(
            'header'    => Mage::helper('casedesign')->__('Image transaction'),
            'width'     => '150px',
            'index'     => 'image_transaction',
            'renderer' => 'Soloyo_Productupload_Block_Adminhtml_Renderer_Imagetransaction'
        ));

        $this->addColumn('admin_comment', array(
            'header'    => Mage::helper('productupload')->__('Admin comment'),
            'index'     => 'admin_comment',
        ));

        $this->addColumn('designer_comment', array(
            'header'    => Mage::helper('productupload')->__('Designer comment'),
            'index'     => 'designer_comment',
        ));

        $this->addColumn('created_time', array(
            'header'    => Mage::helper('productupload')->__('Created Time'),
            'align'     => 'left',
            'index'     => 'created_time',
            'type'      => 'datetime',
        ));

        $this->addColumn('update_time', array(
            'header'    => Mage::helper('productupload')->__('Update Time'),
            'align'     => 'left',
            'index'     => 'update_time',
            'type'      => 'datetime',
        ));

        $this->addColumn('status', array(
            'header'    => Mage::helper('productupload')->__('Status'),
            'align'     => 'left',
            'width'     => '80px',
            'index'     => 'status',
            'type'        => 'options',
            'options'     => array(
                0 => 'Pending',
                1 => 'Approved',
                2 => 'Cancel',
            ),
        ));

        $this->addColumn('action',
            array(
                'header'    =>    Mage::helper('productupload')->__('Action'),
                'width'        => '100',
                'type'        => 'action',
                'getter'    => 'getId',
                'actions'    => array(
                    array(
                        'caption'    => Mage::helper('productupload')->__('Edit'),
                        'url'        => array('base'=> '*/*/edit'),
                        'field'        => 'id'
                    )),
                'filter'    => false,
                'sortable'    => false,
                'index'        => 'stores',
                'is_system'    => true,
        ));

        $this->addExportType('*/*/exportCsv', Mage::helper('productupload')->__('CSV'));
        $this->addExportType('*/*/exportXml', Mage::helper('productupload')->__('XML'));

        return parent::_prepareColumns();
    }
    
    /**
     * prepare mass action for this grid
     *
     * @return Soloyo_Productupload_Block_Adminhtml_Productupload_Grid
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('designer_transaction_id');
        $this->getMassactionBlock()->setFormFieldName('designertransaction');

        $this->getMassactionBlock()->addItem('delete', array(
            'label'        => Mage::helper('productupload')->__('Delete'),
            'url'        => $this->getUrl('*/*/massDelete'),
            'confirm'    => Mage::helper('productupload')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('productupload/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
            'label'=> Mage::helper('productupload')->__('Change status'),
            'url'    => $this->getUrl('*/*/massStatus', array('_current'=>true)),
            'additional' => array(
                'visibility' => array(
                    'name'    => 'status',
                    'type'    => 'select',
                    'class'    => 'required-entry',
                    'label'    => Mage::helper('productupload')->__('Status'),
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