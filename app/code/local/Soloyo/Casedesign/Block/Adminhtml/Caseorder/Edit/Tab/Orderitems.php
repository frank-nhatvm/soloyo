<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 11/14/18
 * Time: 10:49 AM
 */
class Soloyo_Casedesign_Block_Adminhtml_Caseorder_Edit_Tab_Orderitems extends Mage_Adminhtml_Block_Widget_Grid
{
    protected $_caseOrderId;

    /**
     * @return mixed
     */
    public function getCaseOrderId()
    {
        return $this->_caseOrderId;
    }

    /**
     * @param mixed $caseOrderId
     */
    public function setCaseOrderId($caseOrderId)
    {
        $this->_caseOrderId = $caseOrderId;
    }



    public function __construct()
    {
        parent::__construct();
        $this->setId('orderitemsGrid');
        $this->setDefaultSort('caseorder_item_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('casedesign/caseorderitem')
            ->getCollection()->addFieldToFilter('caseorder_id', $this->_caseOrderId);
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('caseorder_item_id', array(
            'header'    => Mage::helper('casedesign')->__('ID'),
            'align'     =>'right',
            'width'     => '50px',
            'index'     => 'caseorder_item_id',
        ));


        $this->addColumn('product_id', array(
            'header'    => Mage::helper('casedesign')->__('Product ID'),
            'index'     => 'product_id',
        ));

        $this->addColumn('design_image', array(
            'header'    => Mage::helper('casedesign')->__('Product image'),
            'index'     => 'design_image',
            'renderer' => 'Soloyo_Casedesign_Block_Adminhtml_Renderer_Imageorderitems'
        ));

        return parent::_prepareColumns();
    }

}