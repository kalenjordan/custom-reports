<?php

/**
 * @author Lee Saferite <lee.saferite@aoe.com>
 * @since  2/28/14
 */
class Clean_SqlReports_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Return a flag indicating if the currently logged in admin user can view reports
     *
     * @return bool
     *
     * @author Lee Saferite <lee.saferite@aoe.com>
     */
    public function getAllowView()
    {
        return $this->getAdminSession()->isAllowed('report/cleansql');
    }

    /**
     * Return a flag indicating if the currently logged in admin user can add/edit reports
     *
     * @return bool
     *
     * @author Lee Saferite <lee.saferite@aoe.com>
     */
    public function getAllowEdit()
    {
        return $this->getAdminSession()->isAllowed('report/cleansql/edit');
    }

    /**
     * Get the admin session singleton
     *
     * @return Mage_Admin_Model_Session
     *
     * @author Lee Saferite <lee.saferite@aoe.com>
     */
    public function getAdminSession()
    {
        return Mage::getSingleton('admin/session');
    }

    /**
     * @param $report Clean_SqlReports_Model_Report
     * @return bool
     */
    public function getPrimaryReportRoute($report)
    {
        if ($report->hasChart()) {
            return 'viewChart';
        } else {
            return 'viewTable';
        }
    }
    /**
     * get active db connection resource config nodes
     * @return Mage_Core_Model_Config_Element
     */
    public function getConnectionResourceConfig()
    {
        $resourceConfig = Mage::getConfig()->getXpath('global/resources/*[child::connection and descendant::active=1]');
        return $resourceConfig;
    }
    /**
     * get default connection resource model
     * @return Magento_Db_Adapter_Pdo_Mysql
     */
    public function getDefaultConnection()
    {
        /* @var $resource Mage_Core_Model_Resource */
        $resource       = Mage::getSingleton('core/resource');
        $connectionName = Mage::getStoreConfig('reports/cleansql/default_connection');
        if (!$connectionName) {
            $connectionName = 'core_read';
        }
        return $resource->getConnection($connectionName);
    }
}
