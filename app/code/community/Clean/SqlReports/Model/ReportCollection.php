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

    /**
     * @todo Refactor this heavily - this is just a rough work in progress
     * @return mixed|string|void
     */
    public function toMultiLineReportJson()
    {
        $results = array();

        /** @var $item Varien_Object */
        foreach ($this as $item) {
            $row = array();
            foreach ($item->getData() as $key => $value) {
                if (is_numeric($value)) {
                    $value = (float)$value;
                }
                $row[] = $value;
            }

            $results[] = $row;
        }

        foreach ($results as $result) {

            $sku = $result[0];
            $hour = $result[1];
            $quantity = $result[2];

            $resultsBySku[$sku][$hour] = $quantity;
        }

        $firstSku = $results[0][0];
        $firstResultBySku = $resultsBySku[$firstSku];

        $newResult = array('Hour');
        foreach ($resultsBySku as $sku => $resultsForSku) {
            $newResult[] = $sku;
        }
        $newResults[] = $newResult;

        foreach ($firstResultBySku as $hour => $quantity) {
            $newResult = array($hour);
            foreach ($resultsBySku as $sku => $resultsForSku) {
                $newResult[] = $resultsForSku[$hour];
            }
            $newResults[] = $newResult;
        }

        $jsonEncoded = json_encode($newResults);
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