<?php

class Clean_SqlReports_Model_Mysql4_Result extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init('cleansql/result', 'id');
    }
}
