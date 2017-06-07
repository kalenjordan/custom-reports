<?php

class Clean_SqlReports_Block_Adminhtml_Page_Menu extends Mage_Adminhtml_Block_Page_Menu
{
    /**
     * Retrieve Adminhtml Menu array
     *
     * @return array
     */
    public function getMenuArray()
    {
        $menuArray = $this->_buildMenuArray();
        if (isset($menuArray['report']) && isset($menuArray['report']['children']) && isset($menuArray['report']['children']['cleansql'])) {
            $this->_appendCleanSqlReports($menuArray['report']['children']['cleansql']);
        }
        return $menuArray;
    }

    /**
     * @param array $menuArray
     */
    protected function _appendCleanSqlReports(array &$menuArray)
    {
        if (!isset($menuArray['children'])) {
            $menuArray['children'] = array();
        }

        $maxReports       = (int) Mage::getStoreConfig('reports/cleansql/max_reports_in_menu');
        $helper           = Mage::helper('cleansql');
        $reportCollection = Mage::getModel('cleansql/report')->getCollection()->setOrder('title', 'ASC');
        $reportCount      = $reportCollection->count();
        $i                = 1;

        foreach ($reportCollection as $report) {
            if (! $helper->getAllowViewReport($report->getId())) {
                continue;
            }

            /** @var $report Clean_SqlReports_Model_Report */
            $titleNodeName = $this->_getXmlTitle($report);
            $route         = $helper->getPrimaryReportRoute($report);

            $menuArray['children'][$titleNodeName] = array(
                'label'      => $report->getTitle(),
                'sort_order' => 0,
                'url'        => $this->getUrl('adminhtml/adminhtml_customreport/' . $route, array('report_id' => $report->getId())),
                'active'     => true,
                'level'      => 2,
                'last'       => $reportCount === $i || $i === $maxReports,
            );
            if ($i === $maxReports) {
                break;
            }
            $i++;
        }
    }

    /**
     * @param Clean_SqlReports_Model_Report $report
     *
     * @return string
     */
    protected function _getXmlTitle(Clean_SqlReports_Model_Report $report)
    {
        return 'report_' . $report->getId();
    }
}
