<?php

class Clean_SqlReports_Block_Adminhtml_Report_View_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    protected $_sqlQueryResults;

    public function __construct()
    {
        parent::__construct();

        $this->setId('reportsGrid');
        $this->setDefaultSort('created_at');
        $this->setDefaultDir('DESC');

        // TODO: remove this direct helper access and replace with an action element in the layout XML
        $this->setAllowRun(Mage::helper('cleansql')->getAllowRun());
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        $this->unsetChild('search_button');
        $this->unsetChild('reset_filter_button');

        return $this;
    }

    /**
     * @return Clean_SqlReports_Model_Report
     */
    protected function getReport()
    {
        return $this->_getHelper()->getCurrentReport();
    }

    /**
     * @return Clean_SqlReports_Helper_Data
     *
     * @author Lee Saferite <lee.saferite@aoe.com>
     */
    protected function _getHelper()
    {
        return Mage::helper('cleansql');
    }

    protected function _prepareCollection()
    {
        if (!$this->getCollection()) {
            $collection = Mage::getModel('cleansql/result')->getCollection()
                ->addFieldToFilter('report_id', $this->getReport()->geTId());

            $this->setCollection($collection);
        }

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn(
            'id',
            array(
                'type'   => 'number',
                'header' => $this->__('ID'),
                'index'  => 'id',
            )
        );

        $this->addColumn(
            'created_at',
            array(
                'type'   => 'datetime',
                'header' => $this->__('Created At'),
                'index'  => 'created_at',
                'width'  => '200px'
            )
        );

        $this->addColumn(
            'start_date',
            array(
                'type'   => 'date',
                'header' => $this->__('Report Period Start'),
                'index'  => 'start_date',
                'width'  => '200px'
            )
        );

        $this->addColumn(
            'end_date',
            array(
                'type'   => 'date',
                'header' => $this->__('Report Period End'),
                'index'  => 'end_date',
                'width'  => '200px'
            )
        );

        $actions = array(
            array(
                'caption' => $this->__('View'),
                'url'     => array(
                    'base'   => '*/sqlReports_result/view',
                    'params' => array(),
                ),
                'field'   => 'id'
            ),
        );

        if ($this->getAllowRun()) {
            $actions[] = array(
                'caption' => $this->__('Delete'),
                'url'     => array(
                    'base'   => '*/sqlReports_result/delete',
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
}
