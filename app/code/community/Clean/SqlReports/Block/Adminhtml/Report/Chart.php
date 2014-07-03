<?php

class Clean_SqlReports_Block_Adminhtml_Report_Chart extends Mage_Adminhtml_Block_Template
{

    protected $_template = 'clean_sqlreports/chart.phtml';

    /**
     * @return Clean_SqlReports_Model_Report
     */
    protected function _getReport()
    {
        return Mage::registry('current_report');
    }

    public function renderChart()
    {
        $_report = $this->_getReport();

        return $_report->getChartConfig();

    }

}