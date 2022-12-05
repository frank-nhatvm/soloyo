<?php

/**
 * Created by PhpStorm.
 * User: frank
 * Date: 9/30/18
 * Time: 11:45 AM
 */
class Soloyo_Productupload_Block_Adminhtml_Designer_Edit_Tab_Casedesign extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('casedesigndGrid');
        $this->setDefaultSort('productupload_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('productupload/productupload')->getCollection();

        if ($this->_designerId) {
            $collection->addFieldToFilter('designer_id', $this->_designerId);
        }
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('productupload_id', array(
            'header'    => Mage::helper('productupload')->__('ID'),
            'align'     =>'right',
            'width'     => '50px',
            'index'     => 'productupload_id',
        ));


        $this->addColumn('product_name', array(
            'header'    => Mage::helper('productupload')->__('Product name'),
            'index'     => 'product_name',
        ));

        $this->addColumn('image_product', array(
            'header'    => Mage::helper('casedesign')->__('Product image'),
            'width'     => '150px',
            'index'     => 'image_product',
            'renderer' => 'Soloyo_Productupload_Block_Adminhtml_Renderer_Image'
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
                3 => 'ReEdit',
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
                        'url'        => array('base'=> 'productuploadadmin/adminhtml_productupload/edit'),
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