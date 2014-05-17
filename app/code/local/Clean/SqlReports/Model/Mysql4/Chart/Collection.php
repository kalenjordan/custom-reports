<?php

class Clean_SqlReports_Model_Mysql4_Chart_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('cleansql/chart');
    }
}
