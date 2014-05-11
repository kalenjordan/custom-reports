<?php

class Clean_SqlReports_Block_Adminhtml_Report_Result_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    protected $_sqlQueryResults;

    public function __construct()
    {
        parent::__construct();
        $this->setId('reportsGrid');
        $this->setSaveParametersInSession(true);
        $this->addExportType('*/*/exportCsv', $this->__('CSV'));
    }

    /**
     * @return Clean_SqlReports_Model_Report
     */
    protected function _getReport()
    {
        return Mage::registry('current_report');
    }

    /**
     * @return Clean_SqlReports_Model_Result
     */
    protected function _getResult()
    {
        return Mage::registry('current_result');
    }

    /**
     * @author Lee Saferite <lee.saferite@aoe.com>
     * @return Varien_Data_Collection_Db
     */
    protected function _createCollection()
    {
        return $this->_getResult()->getResultCollection();
    }

    protected function _prepareCollection()
    {
        if (isset($this->_collection)) {
            return $this->_collection;
        }

        $collection = $this->_createCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $rawColumnConfig = explode(',', $this->_getResult()->getColumnConfig());
        foreach ($rawColumnConfig as $entry) {
            $entry = explode(':', trim($entry));
            if(empty($entry[0])) {
                continue;
            }
            $columnConfig[$entry[0]] = array(
                'type'   => (isset($entry[1]) && !empty($entry[1]) ? $entry[1] : null),
                'name'   => (isset($entry[2]) && !empty($entry[2]) ? $entry[2] : null),
                'filter' => (isset($entry[3]) ? (bool)$entry[3] : true),
                'sort'   => (isset($entry[4]) ? (bool)$entry[4] : true),
            );
        }

        /** @var Varien_Db_Adapter_Interface $connection */
        $connection = $this->_getResult()->getResource()->getReadConnection();
        $tableInfo = $connection->describeTable($this->_getResult()->getResultTable());
        foreach ($tableInfo as $columnKey => $columnData) {
            $type = (isset($columnConfig[$columnKey]['type']) ? $columnConfig[$columnKey]['type'] : $this->mapDdlTypeToColumnType($columnData['DATA_TYPE']));
            $header = (isset($columnConfig[$columnKey]['name']) ? $columnConfig[$columnKey]['name'] : Mage::helper('core')->__($columnKey));
            $filter = (isset($columnConfig[$columnKey]['filter']) && !$columnConfig[$columnKey]['filter'] ? false : null);
            $sortable = (isset($columnConfig[$columnKey]['sort']) ? $columnConfig[$columnKey]['sort'] : true);
            $this->addColumn($columnKey, array('type' => $type, 'header' => $header, 'index' => $columnKey, 'filter' => $filter, 'sortable' => $sortable));
        }

        return parent::_prepareColumns();
    }

    protected function mapDdlTypeToColumnType($ddlType)
    {
        $type = 'text';

        switch ($ddlType) {
            case 'date':
                $type = 'date';
                break;
            case 'timestamp':
            case 'datetime':
                $type = 'datetime';
                break;
            case 'int':
            case 'decimal':
            case 'float':
                $type = 'number';
                break;
        }

        return $type;
    }
}
