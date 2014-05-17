<?php

/**
 * @method int getReportId()
 * @method Clean_SqlReports_Model_Result setReportId(int $value)
 * @method string getCreatedAt()
 * @method Clean_SqlReports_Model_Result setCreatedAt($value)
 * @method string getTableSuffix()
 * @method Clean_SqlReports_Model_Result setTableSuffix(string $value)
 */
class Clean_SqlReports_Model_Result extends Mage_Core_Model_Abstract
{
    const TABLE_CREATE_ATTEMPT_LIMIT = 100;

    public function _construct()
    {
        parent::_construct();
        $this->_init('cleansql/result');
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

    public function getResultTable()
    {
        return $this->getResource()->getMainTable() . '_' . $this->getTableSuffix();
    }

    /**
     * @throws RuntimeException
     * @return Clean_SqlReports_Model_Mysql4_Result_Collection
     *
     * @author Lee Saferite <lee.saferite@aoe.com>
     */
    public function getResultCollection()
    {
        if (!$this->getCollection()->getConnection()->isTableExists($this->getResultTable())) {
            throw new RuntimeException('Invalid table name');
        }

        /** @var Clean_SqlReports_Model_Mysql4_Result_Collection $collection */
        $collection = clone $this->getCollection();
        $collection->setMainTable($this->getResultTable());
        $collection->setItemObjectClass('Varien_Object');

        return $collection;
    }

    protected function _beforeSave()
    {
        parent::_beforeSave();

        if (!$this->getTableSuffix()) {
            $attemptsLeft = self::TABLE_CREATE_ATTEMPT_LIMIT;
            do {
                $this->setTableSuffix(dechex(time()));
                $available = !$this->getResource()->getReadConnection()->isTableExists($this->getResultTable());
            } while (!$available && $attemptsLeft--);

            if (!$available) {
                throw new RuntimeException('Could not create result table suffix');
            }
        }

        return $this;
    }

    public function afterCommitCallback()
    {
        parent::afterCommitCallback();

        if ($this->getTableSuffix()) {
            $table = $this->getResultTable();
            if (!$this->getResource()->getReadConnection()->isTableExists($table)) {
                $this->getResource()->getReadConnection()->query("CREATE TABLE `{$table}` AS {$this->getReport()->getSqlQuery()}");
            }
        }

        return $this;
    }

    protected function _afterDeleteCommit()
    {
        parent::_afterDeleteCommit();

        if ($this->getTableSuffix()) {
            $this->getResource()->getReadConnection()->dropTable($this->getResultTable());
        }

        return $this;
    }
}
