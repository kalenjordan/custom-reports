<?php

/**
 * @method string getCreatedAt()
 * @method Clean_SqlReports_Model_Report setCreatedAt(string $value)
 * @method string getTitle()
 * @method Clean_SqlReports_Model_Report setTitle(string $value)
 * @method string getSqlQuery()
 * @method Clean_SqlReports_Model_Report setSqlQuery(string $query)
 * @method getOutputType()
 * @method Clean_SqlReports_Model_Report setOutputType($value)
 *
 * @method Clean_SqlReports_Model_Report setChartConfig($value)
 */
class Clean_SqlReports_Model_Report extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('cleansql/report');
    }

    public function getReportCollection()
    {
        $connection = Mage::helper('cleansql')->getDefaultConnection();
            
        $collection = Mage::getModel('cleansql/reportCollection', $connection);
        $collection->getSelect()->from(new Zend_Db_Expr('(' . $this->getData('sql_query') . ')'));

        return $collection;
    }

    public function getChartDiv()
    {
        return 'chart_' . $this->getId();
    }
}
