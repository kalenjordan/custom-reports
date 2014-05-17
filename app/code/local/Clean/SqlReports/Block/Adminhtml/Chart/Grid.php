<?php

class Clean_SqlReports_Block_Adminhtml_Chart_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();

        $this->setId('chartsGrid');
        $this->setDefaultSort('title');
        $this->setDefaultDir('ASC');
    }

    protected function _prepareCollection()
    {
        if (!$this->getCollection()) {
            /** @var $collection Clean_SqlReports_Model_Mysql4_Chart_Collection */
            $collection = Mage::getModel('cleansql/chart')->getCollection();
            $this->setCollection($collection);
        }

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn(
            'title',
            array(
                'header' => $this->__('Title'),
                'index'  => 'title',
            )
        );

        $this->addColumn(
            'report_id',
            array(
                'type'    => 'options',
                'header'  => $this->__('Report'),
                'index'   => 'report_id',
                'options' => Mage::getModel('cleansql/report')->getCollection()->toOptionHash()
            )
        );

        $actions = array(
            array(
                'caption' => $this->__('View'),
                'url'     => array(
                    'base'   => '*/*/view',
                    'params' => array(),
                ),
                'field'   => 'id'
            ),
        );

        if ($this->getAllowEdit()) {
            $actions[] = array(
                'caption' => $this->__('Edit'),
                'url'     => array(
                    'base'   => '*/*/edit',
                    'params' => array(),
                ),
                'field'   => 'id'
            );
        }

        $this->addColumn(
            'action_view',
            array(
                'header'     => $this->__('Action'),
                'index'      => 'id',
                'sortable'   => false,
                'filter'     => false,
                'type'       => 'action',
                'actions'    => $actions,
                'link_limit' => 4,
            )
        );

        return parent::_prepareColumns();
    }

    /**
     * @param $item Clean_SqlReports_Model_Report
     *
     * @return string
     */
    public function getRowUrl($item)
    {
        return $this->getUrl('*/*/view', array('id' => $item->getId()));
    }
}
