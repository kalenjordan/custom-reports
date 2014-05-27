<?php
/**
 * Report Grid Config Model
 * @author Rolando Granadino <beeplogic@magenation.com>
 */
class Clean_SqlReports_Model_Report_GridConfig extends Varien_Object
{
    /**
     * get list of filterable columns
     * @return array
     */
    public function getFilterable()
    {
        $filterable = $this->getData('filterable');
        if (is_array($filterable)) {
            return $filterable;
        }
        return array();
    }
}