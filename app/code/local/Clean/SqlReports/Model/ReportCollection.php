<?php
/**
 * Class ${NAME}
 *
 * @author Fabrizio Branca
 * @since 2014-05-10
 */
class Clean_SqlReports_Model_ReportCollection extends Varien_Data_Collection_Db
{
    public function toReportJson()
    {
        $results = array();

        /** @var $item Varien_Object */
        foreach ($this as $item) {
            $results[] = array($item->getData('label'), (int)$item->getData('value'));
        }

        $jsonEncoded = json_encode($results);
        return $jsonEncoded;
    }
}