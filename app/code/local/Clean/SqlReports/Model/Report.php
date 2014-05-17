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

    /**
     * Run this report
     *
     * @return Clean_SqlReports_Model_Result
     *
     * @author Lee Saferite <lee.saferite@aoe.com>
     */
    public function run()
    {
        /** @var Clean_SqlReports_Model_Result $result */
        $result = Mage::getModel('cleansql/result');
        $result->setReportId($this->getId());
        $result->setColumnConfig($this->getColumnConfig());
        $result->setCreatedAt(Mage::app()->getLocale()->storeDate(null, null, true));
        $result->save();

        return $result;
    }

    /**
     * @return Clean_SqlReports_Model_Result
     *
     * @author Lee Saferite <lee.saferite@aoe.com>
     */
    public function getLatestResult()
    {
        return Mage::getModel('cleansql/result')->getCollection()
            ->addFieldToFilter('report_id', $this->getId())
            ->addOrder('created_at', 'DESC')
            ->getFirstItem();
    }

    protected function _beforeSave()
    {
        parent::_beforeSave();

        $columnConfig = $this->getColumnConfig();
        $columnConfig = implode(',', array_filter(array_map('trim', explode("\n", $columnConfig))));
        $this->setColumnConfig($columnConfig);

        return $this;
    }

    /**
     * Delete object from database
     *
     * @return Mage_Core_Model_Abstract
     */
    public function delete()
    {
        Mage::getModel('cleansql/result')->getCollection()
            ->addFieldToFilter('report_id', $this->getId())
            ->walk('delete');

        return parent::delete();
    }
}
