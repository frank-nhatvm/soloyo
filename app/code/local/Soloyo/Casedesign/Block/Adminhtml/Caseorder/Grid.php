<?php

class Soloyo_Casedesign_Block_Adminhtml_Caseorder_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('caseorderGrid');
        $this->setDefaultSort('caseorder_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    /**
     * prepare collection for block to display
     *
     * @return Soloyo_Casedesign_Block_Adminhtml_Casedesign_Grid
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('casedesign/caseorder')->getCollection();

        $collection->join(array('order'=> 'sales/order'),'main_table.order_id = order.entity_id',array('order_status'=>'order.status'));

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
        $this->addColumn('caseorder_id', array(
            'header'    => Mage::helper('casedesign')->__('ID'),
            'align'     =>'right',
            'width'     => '50px',
            'index'     => 'caseorder_id',
        ));

        $this->addColumn('order_id', array(
            'header'    => Mage::helper('casedesign')->__('Order ID'),
            'align'     =>'left',
            'index'     => 'order_id',
            'renderer' => 'Soloyo_Casedesign_Block_Adminhtml_Renderer_Orderlink'
        ));

        $this->addColumn('printer_id', array(
            'header'    => Mage::helper('casedesign')->__('Printer ID'),
            'align'     =>'left',
            'index'     => 'printer_id',
            'renderer' => 'Soloyo_Casedesign_Block_Adminhtml_Renderer_Printer'
        ));

        $this->addColumn('order_status', array(
            'header' => Mage::helper('casedesign')->__('Order status'),
            'index' => 'order_status',
            'type'  => 'options',
            'width' => '70px',
            'options' => Mage::getSingleton('sales/order_config')->getStatuses(),
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

        $this->addColumn('is_customer_design', array(
            'header'    => Mage::helper('casedesign')->__('Custom Design'),
            'align'     => 'left',
            'width'     => '80px',
            'index'     => 'is_customer_design',
            'type'        => 'options',
            'options'     => array(

                1 =>  'Yes',
                0 => 'No',

            )
        ));

        $this->addColumn('status', array(
            'header'    => Mage::helper('casedesign')->__('Status'),
            'align'     => 'left',
            'width'     => '80px',
            'index'     => 'status',
            'type'        => 'options',
            'options'     => array(
                0 => 'Not send',
                1 =>  'Sent',
                2 => 'Printing',
                3 => 'Printed'
            )
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
        $this->setMassactionIdField('caseorder_id');
        $this->getMassactionBlock()->setFormFieldName('caseorder');
        $printers = $this->getListPrinters();
        $this->getMassactionBlock()->addItem('printer_id', array(
            'label'        => Mage::helper('casedesign')->__('Send to'),
            'url'        => $this->getUrl('*/*/massSendToPrinter'),
            'additional' => array(
                'visibility' => array(
                    'name'    => 'printer_id',
                    'type'    => 'select',
                    'class'    => 'required-entry',
                    'label'    => Mage::helper('casedesign')->__('Printer'),
                    'values'=> $printers
                ))
        ));


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

    protected function getListPrinters(){
        $collection = Mage::getModel('printer/printer')->getCollection()
            ->addFieldToFilter('status','1');

        $result = array();
        $result[] = ['value'=>0,'label'=>'Select a printer'];
        foreach ($collection as $printer){
            $item = array();
            $item['value'] = $printer->getId();
            $item['label'] = $printer->getName();
            $result[] = $item;
        }

        return $result;
    }

    /**
     * get url for each row in grid
     *
     * @return string
     */
    public function getRowUrl($row)
    {
        return false;
        //return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
}