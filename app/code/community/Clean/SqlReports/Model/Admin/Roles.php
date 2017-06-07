<?php

/**
 * This rewrite adds the custom reports as ACL paths to the resources tree
 *
 * Class Clean_SqlReports_Model_Admin_Roles
 */
class Clean_SqlReports_Model_Admin_Roles extends Mage_Admin_Model_Roles {

    /**
     * Return tree of acl resources
     *
     * @return array|null|Varien_Simplexml_Element
     */
    public function getResourcesTree()
    {
        $resourcesTreeArray = parent::getResourcesTree();
        /** @var Varien_Simplexml_Element $node */
        $node = $resourcesTreeArray->admin->children->report->children->cleansql->children;

        // Get all reports
        $reportCollection = Mage::getModel('cleansql/report')->getCollection()->setOrder('title', 'ASC');

        // Add them to the roles structure
        foreach ($reportCollection as $report) {
            $reportChildNode = $node->addChild('report_' . $report->getId());
            $reportChildNode->addAttribute('aclpath', Clean_SqlReports_Helper_Data::RESOURCE_VIEW_REPORT_PREFIX . $report->getId());
            $reportChildNode->addChild('title', 'Report: ' . $report->getTitle());
        }

        return $resourcesTreeArray;
    }

    /**
     * Return list of acl resources
     *
     * @return array|null|Varien_Simplexml_Element
     */
    public function getResourcesList()
    {
        $resourcesList = parent::getResourcesList();

        // Get all reports
        $reportCollection = Mage::getModel('cleansql/report')->getCollection()->setOrder('title', 'ASC');

        // Add them to the roles structure
        foreach ($reportCollection as $report) {
            $resourcesList[Clean_SqlReports_Helper_Data::RESOURCE_VIEW_REPORT_PREFIX . $report->getId()] = [
                'name' => 'Report: ' . $report->getTitle(),
                'level' => null // not important
            ];
        }

        return $resourcesList;
    }

    /**
     * Return list of acl resources in 2D format
     *
     * @return array|null|Varien_Simplexml_Element
     */
    public function getResourcesList2D()
    {
        $resourcesList2D = parent::getResourcesList2D();

        // Get all reports
        $reportCollection = Mage::getModel('cleansql/report')->getCollection()->setOrder('title', 'ASC');

        foreach ($reportCollection as $report) {
            $resourcesList2D[] = Clean_SqlReports_Helper_Data::RESOURCE_VIEW_REPORT_PREFIX . $report->getId();
        }

        return $resourcesList2D;
    }
}