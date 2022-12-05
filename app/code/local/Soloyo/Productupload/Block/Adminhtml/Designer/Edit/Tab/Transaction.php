<?php

/**
 * Created by PhpStorm.
 * User: frank
 * Date: 9/30/18
 * Time: 11:45 AM
 */
class Soloyo_Productupload_Block_Adminhtml_Designer_Edit_Tab_Transaction extends Mage_Adminhtml_Block_Widget_Grid
{
    protected $_designerId;

    /**
     * @return mixed
     */
    public function getDesignerId()
    {
        return $this->_designerId;
    }

    /**
     * @param mixed $designerId
     */
    public function setDesignerId($designerId)
    {
        $this->_designerId = $designerId;
    }

    public function __construct()
    {
        parent::__construct();
        $this->setId('transactionGrid');
        $this->setDefaultSort('designer_transaction_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('productupload/designertransaction')->getCollection();

        if ($this->_designerId) {
            $collection->addFieldToFilter('designer_id', $this->_designerId);
        }
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
            'header'    => Mage::helper('productupload')->__('Image transaction'),
            'width'     => '150px',
            'index'     => 'image_transaction',
            'renderer' => 'Soloyo_Productupload_Block_Adminhtml_Renderer_Imagetransaction'
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
                        'url'        => array('base'=> 'productuploadadmin/adminhtml_transaction/edit'),
                        'field'        => 'id'
                    )),
                'filter'    => false,
                'sortable'    => false,
                'index'        => 'stores',
                'is_system'    => true,
            ));


        return parent::_prepareColumns();
    }

}