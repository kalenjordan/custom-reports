<?php

class Clean_SqlReports_Block_Adminhtml_Report_View_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    protected $_sqlQueryResults;

    public function __construct()
    {
        parent::__construct();
        $this->setId('reportsGrid');
        $this->setDefaultSort('report_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->addExportType('*/*/exportCsv', $this->__('CSV'));
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
    protected function _getReport()
    {
        return Mage::registry('current_report');
    }

    protected function _getSqlQueryResults()
    {
        if (isset($this->_sqlQueryResults)) {
            return $this->_sqlQueryResults;
        }

        $report = $this->_getReport();
        $sql = $report->getData('sql_query');

        $connection = Mage::getSingleton('core/resource')->getConnection('core_read');
        $results = $connection->fetchAll($sql);

        $this->_sqlQueryResults = $results;
        return $this->_sqlQueryResults;
    }

    protected function _prepareCollection()
    {
        if (isset($this->_collection)) {
            return $this->_collection;
        }

        $collection = new Varien_Data_Collection();
        foreach ($this->_getSqlQueryResults() as $result) {
            $collection->addItem(new Varien_Object($result));
        }

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $results = $this->_getSqlQueryResults();
        if (!isset($results[0]) || ! is_array($results[0])) {
            return parent::_prepareColumns();
        }

        foreach ($results[0] as $key => $val) {
            $this->addColumn($key, array(
                'header'    => Mage::helper('core')->__($key),
                'index'     => $key,
                'filter'    => false,
                'sortable'  => false,
            ));
        }

        return parent::_prepareColumns();
    }
}