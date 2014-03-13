<?php

class Clean_SqlReports_Block_Adminhtml_Report_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('reportsGrid');
        $this->setDefaultSort('report_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        /** @var $collection Clean_SqlReports_Model_Mysql4_Report_Collection */
        $collection = Mage::getModel('cleansql/report')->getCollection();
        $collection->setOrder('title', 'ASC');

        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('report_id', array(
            'header'    => Mage::helper('core')->__('ID'),
            'width'     => '50px',
            'index'     => 'report_id',
        ));

        $this->addColumn('title', array(
            'header'    => Mage::helper('core')->__('Title'),
            'index'     => 'title',
        ));

        $this->addColumn('action_view', array(
            'header'    => Mage::helper('adminhtml')->__(''),
            'index'     => 'report_id',
            'sortable'  => false,
            'filter'    => false,
            'type'      => 'action',
            'actions'   => array(
                array(
                    'caption' => Mage::helper('adminhtml')->__('View Report Results'),
                    'url'     => array(
                        'base'      => 'admin_cleansql/adminhtml_report/view',
                        'params'    => array(),
                    ),
                    'field'   => 'report_id'
                ),
            ),
        ));

        return parent::_prepareColumns();
    }

    /**
     * @param $item Clean_SqlReports_Model_Report
     */
    public function getRowUrl($item)
    {
        return $this->getUrl('*/*/edit', array('report_id' => $item->getId()));
    }
}