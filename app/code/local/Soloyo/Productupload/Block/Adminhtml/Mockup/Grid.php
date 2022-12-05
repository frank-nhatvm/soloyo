<?php

class Soloyo_Productupload_Block_Adminhtml_Mockup_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('productuploadGrid');
        $this->setDefaultSort('mockup_id');
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
        $collection = Mage::getModel('productupload/mockup')->getCollection();


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
        $this->addColumn('mockup_id', array(
            'header'    => Mage::helper('productupload')->__('ID'),
            'align'     =>'right',
            'width'     => '50px',
            'index'     => 'mockup_id',
        ));

        $this->addColumn('designer_id', array(
            'header'    => Mage::helper('productupload')->__('Upload by'),
            'index'     => 'designer_id',
            'renderer'     => 'Soloyo_Productupload_Block_Adminhtml_Renderer_Mockupdesignerlink',
        ));


        $this->addColumn('brand_id', array(
            'header'    => Mage::helper('productupload')->__('Brand'),
            'index'     => 'brand_id',
            'type'        => 'options',
            'options' => Mage::getModel('brandmodel/brandmobile')->getAllOriginal(),
        ));

        $this->addColumn('model_id', array(
            'header'    => Mage::helper('productupload')->__('Phone'),
            'index'     => 'model_id',
            'type'        => 'options',
            'options' =>Mage::getModel('brandmodel/modelmobile')->getAllOriginal()
        ));

        $this->addColumn('status', array(
            'header'    => Mage::helper('productupload')->__('Status'),
            'align'     => 'left',
            'width'     => '80px',
            'index'     => 'status',
            'type'        => 'options',
            'options'     => array(
                0 => 'Disable',
                1 => 'Enable',
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
        $this->setMassactionIdField('mockup_id');
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