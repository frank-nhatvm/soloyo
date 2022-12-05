<?php

class Soloyo_Productupload_Block_Adminhtml_Imageprint_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('imageprintGrid');
        $this->setDefaultSort('imageprint_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('productupload/imageprint')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
    

    protected function _prepareColumns()
    {
        $this->addColumn('imageprint_id', array(
            'header'    => Mage::helper('productupload')->__('ID'),
            'align'     =>'right',
            'width'     => '50px',
            'index'     => 'imageprint_id',
        ));

        $this->addColumn('product_id', array(
            'header'    => Mage::helper('productupload')->__('Product ID'),
            'index'     => 'product_id',
        ));


        $this->addColumn('image_product', array(
            'header'    => Mage::helper('productupload')->__('Image Product'),
            'index'     => 'image_product',
            'renderer'     => 'Soloyo_Productupload_Block_Adminhtml_Renderer_Image',
        ));

        return parent::_prepareColumns();
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