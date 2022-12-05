<?php

class Soloyo_Soloyo_Block_Adminhtml_Item_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('itemGrid');
        $this->setDefaultSort('homeitem_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    /**
     * prepare collection for block to display
     *
     * @return Soloyo_Home_Block_Adminhtml_Home_Grid
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('soloyo/homeitem')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare columns for this grid
     *
     * @return Soloyo_Home_Block_Adminhtml_Home_Grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn('homeitem_id', array(
            'header' => Mage::helper('soloyo')->__('ID'),
            'align' => 'right',
            'width' => '50px',
            'index' => 'homeitem_id',
        ));

        $this->addColumn('name', array(
            'header' => Mage::helper('soloyo')->__('Name'),
            'align' => 'left',
            'index' => 'name',
        ));
        $this->addColumn('cate_id', array(
            'header' => Mage::helper('soloyo')->__('Category Id'),
            'align' => 'left',
            'index' => 'cate_id',
        ));

        $this->addColumn('parent_id', array(
            'header' => Mage::helper('soloyo')->__('Parent Id'),
            'align' => 'left',
            'index' => 'parent_id',
        ));

        $this->addColumn('image', array(
            'header' => Mage::helper('soloyo')->__('Image'),
            'width' => '150px',
            'index' => 'image',
            'renderer' => 'Soloyo_Soloyo_Block_Adminhtml_Renderer_Itemimage'
        ));

        $this->addColumn('url', array(
            'header' => Mage::helper('soloyo')->__('Url'),
            'width' => '150px',
            'index' => 'url',
        ));
        $this->addColumn('position', array(
            'header' => Mage::helper('soloyo')->__('Position'),
            'width' => '150px',
            'index' => 'position',
        ));

        $this->addColumn('type', array(
            'header' => Mage::helper('soloyo')->__('Type'),
            'align' => 'left',
            'width' => '80px',
            'index' => 'type',
            'type' => 'options',
            'options' => array(
                0 => 'Product',
                1 => 'Category',
            ),
        ));
        $this->addColumn('status', array(
            'header' => Mage::helper('soloyo')->__('Status'),
            'align' => 'left',
            'width' => '80px',
            'index' => 'status',
            'type' => 'options',
            'options' => array(
                1 => 'Enabled',
                0 => 'Disabled',
            ),
        ));

        $this->addColumn('action',
            array(
                'header' => Mage::helper('soloyo')->__('Action'),
                'width' => '100',
                'type' => 'action',
                'getter' => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('soloyo')->__('Edit'),
                        'url' => array('base' => '*/*/edit'),
                        'field' => 'id'
                    )),
                'filter' => false,
                'sortable' => false,
                'index' => 'stores',
                'is_system' => true,
            ));


        return parent::_prepareColumns();
    }


    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('homeitem_id');
        $this->getMassactionBlock()->setFormFieldName('homeitem');

        $this->getMassactionBlock()->addItem('delete', array(
            'label' => Mage::helper('soloyo')->__('Delete'),
            'url' => $this->getUrl('*/*/massDelete'),
            'confirm' => Mage::helper('soloyo')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('soloyo/status')->getOptionArray();

        array_unshift($statuses, array('label' => '', 'value' => ''));
        $this->getMassactionBlock()->addItem('status', array(
            'label' => Mage::helper('soloyo')->__('Change status'),
            'url' => $this->getUrl('*/*/massStatus', array('_current' => true)),
            'additional' => array(
                'visibility' => array(
                    'name' => 'status',
                    'type' => 'select',
                    'class' => 'required-entry',
                    'label' => Mage::helper('soloyo')->__('Status'),
                    'values' => $statuses
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