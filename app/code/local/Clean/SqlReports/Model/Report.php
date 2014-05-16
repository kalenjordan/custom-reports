<?php

/**
 * @method getCreatedAt()
 * @method Clean_SqlReports_Model_Report setCreatedAt($value)
 * @method getTitle()
 * @method Clean_SqlReports_Model_Report setTitle($value)
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
        /** @var Mage_Core_Model_Resource $resource */
        $resource = Mage::getSingleton('core/resource');
        $connectionName = Mage::getStoreConfig('reports/cleansql/default_connection');
        if (!$connectionName) {
            $connectionName = 'core_read';
        }
        $connection = $resource->getConnection($connectionName);
            
        $collection = Mage::getModel('cleansql/reportCollection', $connection);
        $collection->getSelect()->from(new Zend_Db_Expr('(' . $this->getData('sql_query') . ')'));

        return $collection;
    }

    public function getChartDiv()
    {
        return 'chart_' . $this->getId();
    }

    public function hasChart()
    {
        if (! $this->getOutputType()) {
            return false;
        }

        if ($this->getOutputType() == Clean_SqlReports_Model_Config_OutputType::TYPE_PLAIN_TABLE) {
            return false;
        }

        return true;
    }
}