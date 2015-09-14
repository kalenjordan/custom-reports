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
    /**
     * @var Clean_SqlReports_Model_Report_GridConfig
     */
    protected $_gridConfig = null;
    
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
    /**
     * @return Clean_SqlReports_Model_Report_GridConfig
     */
    public function getGridConfig()
    {
        if (!$this->_gridConfig) {
            $config = json_decode($this->getData('grid_config'), true);
            if (!is_array($config)) {
                $config = array();
            }
            $this->_gridConfig = Mage::getModel('cleansql/report_gridConfig', $config);
        }
        return $this->_gridConfig;
    }

    /**
     * Disallow TRUNCATE, DROP, DELETE statements & remove semicolon terminator
     */
    protected function _beforeSave() {
        $disallowedPatterns = array(
            'TRUNCATE TABLE',
            'DROP TABLE',
            'DROP TEMPORARY TABLE',
            'DELETE FROM'
        );
		
        $sqlQuery = $this->getSqlQuery();

        if (substr($sqlQuery, -1) === ';') {
            $this->setSqlQuery(substr($sqlQuery, 0, -1));
            Mage::getSingleton('adminhtml/session')->addNotice(Mage::helper('cleansql')->__('Do not include a semicolon terminator'));
        }

        foreach ($disallowedPatterns as $pattern) {
            if (stripos($sqlQuery, $pattern) !== false) {
				$this->_dataSaveAllowed = false;
				Mage::getSingleton('adminhtml/session')->addError(Mage::helper('cleansql')->__('TRUNCATE, DROP or DELETE statemanets are not allowed'));
				return $this;
		    }
        }

        Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('cleansql')->__('Saved report: %s', $this->getTitle()));

        return $this;		
    }
}
