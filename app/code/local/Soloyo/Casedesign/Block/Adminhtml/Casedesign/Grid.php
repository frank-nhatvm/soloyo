<?php

class Soloyo_Casedesign_Block_Adminhtml_Casedesign_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('casedesignGrid');
        $this->setDefaultSort('casedesign_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    /**
     * prepare collection for block to display
     *
     * @return Soloyo_Casedesign_Block_Adminhtml_Casedesign_Grid
     */
    protected function _prepareCollection()
    {
        $collection = Mage::helper('casedesign')->getCaseDesignProduct();
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
        $this->addColumn('entity_id', array(
            'header'    => Mage::helper('casedesign')->__('ID'),
            'align'     =>'right',
            'width'     => '50px',
            'index'     => 'entity_id',
        ));

        $this->addColumn('name', array(
            'header'    => Mage::helper('casedesign')->__('Name'),
            'align'     =>'left',
            'index'     => 'name',
        ));

        $this->addColumn('has_design', array(
            'header'    => Mage::helper('casedesign')->__('Has Design'),
            'align'     =>'center',
            'width'     => '120px',
            'index'     => 'has_design',
            'sortable'  => false,
            'type'      => 'options',
            'options'   => array(
                1 => 'Has Design',
                2 => 'No Design',
            ),
            'renderer'  => 'Soloyo_Casedesign_Block_Adminhtml_Renderer_Design',
            'filter_condition_callback' => array($this, '_filterDesignCondition'),
        ));


        $this->addColumn('action',
            array(
                'header'    =>    Mage::helper('casedesign')->__('Action'),
                'width'        => '100',
                'type'        => 'action',
                'getter'    => 'getId',
                'actions'    => array(
                    array(
                        'caption'    => Mage::helper('casedesign')->__('Create design'),
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
        $this->setMassactionIdField('casedesign_id');
        $this->getMassactionBlock()->setFormFieldName('casedesign');

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

    protected function _filterDesignCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }

        $model = Mage::getModel('casedesign/casedesign')
            ->getCollection()
            ->addFieldToFilter('status', 1)
        ;

        $product_ids = array();
        foreach ($model as $m){
            $product_ids[] = $m->getProductId();
        }

        if($value == 1) {
            $this->getCollection()
                ->addAttributeToFilter('entity_id', array('in' => $product_ids))
                ->load();
        } else {
            $this->getCollection()
                ->addAttributeToFilter('entity_id', array('nin' => $product_ids))
                ->load();
        }

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