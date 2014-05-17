<?php

class Clean_SqlReports_Model_Mysql4_Report_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected function _construct()
    {
        parent::_construct();
        $this->_init('cleansql/report');
    }

    protected function _toOptionArray($valueField = 'report_id', $labelField = 'title', $additional = array())
    {
        return parent::_toOptionArray($valueField, $labelField, $additional);
    }

    protected function _toOptionHash($valueField = 'report_id', $labelField = 'title')
    {
        return parent::_toOptionHash($valueField, $labelField);
    }
}
