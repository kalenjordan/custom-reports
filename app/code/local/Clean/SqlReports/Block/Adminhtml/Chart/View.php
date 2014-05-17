<?php

class Clean_SqlReports_Block_Adminhtml_Chart_View extends Mage_Adminhtml_Block_Template
{
    protected $_template = 'clean_sqlreports/chart.phtml';

    /**
     * @return Clean_SqlReports_Model_Result
     */
    public function getResult()
    {
        return $this->_getHelper()->getCurrentResult();
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

    public function renderChart()
    {
        $result = $this->getResult();

        return $result->getReport()->getChartConfig();
    }
}
