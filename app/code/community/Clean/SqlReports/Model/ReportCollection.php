<?php
/**
 * Class ${NAME}
 *
 * @author Fabrizio Branca
 * @since 2014-05-10
 */
class Clean_SqlReports_Model_ReportCollection extends Varien_Data_Collection_Db
{
    // There should be a collection model specific to the pie chart type which
    // has this specific json handling.  We should map a dropdown type field
    // on the report definition to map to this.
    public function toReportJson()
    {
        $results = array();

        $first = true;

        /** @var $item Varien_Object */
        foreach ($this as $item) {
            if ($first) {
                $labels = array();
                foreach ($item->getData() as $key => $value) {
                    $labels[] = $key;
                }
                $results[] = $labels;
                $first = false;
            }
            $row = array();
            foreach ($item->getData() as $key => $value) {
                if (is_numeric($value)) {
                    $value = (float)$value;
                }
                $row[] = $value;
            }

            $results[] = $row;
        }

        $jsonEncoded = json_encode($results);
        return $jsonEncoded;
    }

    public function toCalendarJson() {
        $results = array();
        foreach ($this as $item) {
            $row = array();
            foreach ($item->getData() as $key => $value) {
                if (is_numeric($value) && $value < 1000000) {
                    $value = (float)$value;
                }
                $row[] = $value;
            }
            $results[] = $row;
        }

        $jsonEncoded = json_encode($results);
        return $jsonEncoded;
    }

}