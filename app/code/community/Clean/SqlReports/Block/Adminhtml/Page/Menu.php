<?php

class Clean_SqlReports_Block_Adminhtml_Page_Menu extends Mage_Adminhtml_Block_Page_Menu
{

    /**
     * Recursive Build Menu array
     *
     * @param Varien_Simplexml_Element $parent
     * @param string                   $path
     * @param int                      $level
     *
     * @return array
     */
    protected function _buildMenuArray(Varien_Simplexml_Element $parent = null, $path = '', $level = 0)
    {
        if (null === $parent) {
            $parent = Mage::getSingleton('admin/config')->getAdminhtmlConfig()->getNode('menu');

            if (isset($parent->children()->report)) {
                /** @var Varien_Simplexml_Element $cleanNode */
                $cleanNode = $parent->children()->report->children()->children->cleansql;
                $this->_appendCleanSqlReports($cleanNode);
            }
        }
        return parent::_buildMenuArray($parent, $path, $level);
    }

    /**
     * @param Varien_Simplexml_Element $node
     */
    protected function _appendCleanSqlReports(Varien_Simplexml_Element $node)
    {
        $reportCollection = Mage::getModel('cleansql/report')->getCollection()->setOrder('title', 'ASC');
        foreach ($reportCollection as $report) {
            /** @var $report Clean_SqlReports_Model_Report */
            $titleNodeName = $this->_getXmlTitle($report);
            $node->setNode('children/' . $titleNodeName . '/title', $report->getTitle());
            $route     = Mage::helper('cleansql')->getPrimaryReportRoute($report);
            $fullRoute = '*/*/' . $route . '/report_id/' . $report->getId();
            $node->setNode('children/' . $titleNodeName . '/action', $fullRoute);
        }
    }

    /**
     * @param Clean_SqlReports_Model_Report $report
     *
     * @return string
     */
    protected function _getXmlTitle(Clean_SqlReports_Model_Report $report)
    {
        return strtolower(preg_replace('~[^a-z0-9]+~i', '', $report->getTitle()));
    }
}
