<?php

/**
 * @method int getReportId()
 * @method Clean_SqlReports_Model_Chart setReportId(int $value)
 * @method string getTitle()
 * @method Clean_SqlReports_Model_Report setTitle(string $value)
 * @method string getSqlQuery()
 * @method Clean_SqlReports_Model_Report setSqlQuery(string $query)
 * @method string getOutputType()
 * @method Clean_SqlReports_Model_Report setOutputType($value)
 * @method string getChartConfig()
 * @method Clean_SqlReports_Model_Report setChartConfig($value)
 */
class Clean_SqlReports_Model_Chart extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        $this->_init('cleansql/chart');
    }

    /**
     * @return Clean_SqlReports_Model_Report
     *
     * @author Lee Saferite <lee.saferite@aoe.com>
     */
    public function getReport()
    {
        return Mage::getModel('cleansql/report')->load($this->getReportId());
    }

    /**
     * @param Clean_SqlReports_Model_Result $result
     *
     * @return Varien_Data_Collection_Db
     *
     * @throws RuntimeException
     *
     * @author Lee Saferite <lee.saferite@aoe.com>
     */
    public function getChartCollection(Clean_SqlReports_Model_Result $result = null)
    {
        // Grab SQL
        $sql = $this->getSqlQuery();

        // If linked to a report and result is null, grab most recent result
        if ((!$result || !$result->getId()) && $this->getReportId()) {
            $result = $this->getReport()->getLatestResult();
        }

        // If there is a result, replace {{table}} with real table name in SQL
        if ($result && $result->getId()) {
            $sql = str_replace('{{table}}', "`{$result->getResultTable()}`", $sql);
        }

        // If there is still a {{table}} in the SQL, toss an exception
        if (strpos($sql, '{{table}}') !== false) {
            throw new RuntimeException('Invalid SQL statement. To run this statement a valid result is required.');
        }

        // Wrap SQL into a sub-select on a generic collection object (this allows downstream filtering)
        /** @var $connection Varien_Db_Adapter_Interface */
        $connection = $this->getResource()->getReadConnection();
        $collection = new Varien_Data_Collection_Db($connection);
        $collection->getSelect()->from(new Zend_Db_Expr("({$sql})"));

        return $collection;
    }

    public function getChartDiv() {
        return 'chart_' . $this->getId();
    }
}
