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
}
