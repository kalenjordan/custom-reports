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
        $connection = $resource->getConnection('core_read');

        $collection = new Clean_SqlReports_Model_ReportCollection($connection);
        $collection->getSelect()->from(new Zend_Db_Expr("(" . $this->getData('sql_query') . ")"));

        return $collection;
    }

    public function getChartConfig()
    {
        $chartConfig = $this->getData('chart_config');
        if (strpos($chartConfig, '{{var json_data}}') !== false) {
            $json = $this->getReportCollection()->toReportJson();
            $chartConfig = str_replace("{{var json_data}}", $json, $chartConfig);
        }
        $chartConfig = str_replace("{{json_url}}", Mage::getUrl('adminhtml/adminhtml_report/getJson', array('report_id' => $this->getId())), $chartConfig);
        $chartConfig = str_replace("{{chart_div}}", $this->getChartDiv(), $chartConfig);
        return $chartConfig;
    }

    public function getChartDiv() {
        return 'chart_' . $this->getId();
    }
}