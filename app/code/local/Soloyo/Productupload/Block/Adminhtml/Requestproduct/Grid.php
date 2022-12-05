<?php

class Soloyo_Productupload_Block_Adminhtml_Requestproduct_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('productuploadGrid');
        $this->setDefaultSort('requestproduct_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }
    
    /**
     * prepare collection for block to display
     *
     * @return Soloyo_Productupload_Block_Adminhtml_Productupload_Grid
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('productupload/requestproduct')->getCollection();


        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
    
    /**
     * prepare columns for this grid
     *
     * @return Soloyo_Productupload_Block_Adminhtml_Productupload_Grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn('requestproduct_id', array(
            'header'    => Mage::helper('productupload')->__('ID'),
            'align'     =>'right',
            'width'     => '50px',
            'index'     => 'requestproduct_id',
        ));

        $this->addColumn('user_id', array(
            'header'    => Mage::helper('productupload')->__('User'),
            'index'     => 'user_id',
            'renderer'     => 'Soloyo_Productupload_Block_Adminhtml_Renderer_Userlink',
        ));


        $this->addColumn('email', array(
            'header'    => Mage::helper('productupload')->__('Email'),
            'index'     => 'email',
        ));

        $this->addColumn('phone', array(
            'header'    => Mage::helper('productupload')->__('Phone'),
            'index'     => 'phone',
        ));

        $this->addColumn('product_id', array(
            'header'    => Mage::helper('productupload')->__('Product'),
            'renderer'     => 'Soloyo_Productupload_Block_Adminhtml_Renderer_Product',
            'index'     => 'product_id',
        ));

        $this->addColumn('requirement', array(
            'header'    => Mage::helper('productupload')->__('Requirement'),
            'index'     => 'requirement',
        ));


        $this->addColumn('designer_id', array(
            'header'    => Mage::helper('productupload')->__('Designer Id'),
            'index'     => 'designer_id',
            'renderer'     => 'Soloyo_Productupload_Block_Adminhtml_Renderer_Designerlink',
        ));

        $this->addColumn('status', array(
            'header'    => Mage::helper('productupload')->__('Status'),
            'align'     => 'left',
            'width'     => '80px',
            'index'     => 'status',
            'type'        => 'options',
            'options'     => array(
                0 => 'Pending',
                1 => 'Sent to Designer',
                2 => 'Updated',
                3 => 'Cancel',
                4 => 'Designer not update',
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
        $this->setMassactionIdField('requestproduct_id');
        $this->getMassactionBlock()->setFormFieldName('requestproduct');

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