<?php

/**
 * @author Lee Saferite <lee.saferite@aoe.com>
 * @since  2/28/14
 */
class Clean_SqlReports_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function getCurrentReport()
    {
        /** @var Clean_SqlReports_Model_Report $model */
        $model = Mage::registry('current_clean_sqlreports_report');

        if (!$model instanceof Clean_SqlReports_Model_Report) {
            $model = Mage::getModel('cleansql/report');
            $this->setCurrentReport($model);
        }

        return $model;
    }

    public function setCurrentReport(Clean_SqlReports_Model_Report $model)
    {
        Mage::unregister('current_clean_sqlreports_report');
        Mage::register('current_clean_sqlreports_report', $model);

        return $this;
    }

    public function getCurrentResult()
    {
        /** @var Clean_SqlReports_Model_Result $model */
        $model = Mage::registry('current_clean_sqlreports_result');

        if (!$model instanceof Clean_SqlReports_Model_Result) {
            $model = Mage::getModel('cleansql/result');
            $this->setCurrentResult($model);
        }

        return $model;
    }

    public function setCurrentResult(Clean_SqlReports_Model_Result $model)
    {
        Mage::unregister('current_clean_sqlreports_result');
        Mage::register('current_clean_sqlreports_result', $model);

        return $this;
    }

    public function getCurrentChart()
    {
        /** @var Clean_SqlReports_Model_Chart $model */
        $model = Mage::registry('current_clean_sqlreports_chart');

        if (!$model instanceof Clean_SqlReports_Model_Chart) {
            $model = Mage::getModel('cleansql/chart');
            $this->setCurrentChart($model);
        }

        return $model;
    }

    public function setCurrentChart(Clean_SqlReports_Model_Chart $model)
    {
        Mage::unregister('current_clean_sqlreports_chart');
        Mage::register('current_clean_sqlreports_chart', $model);

        return $this;
    }

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
     * Return a flag indicating if the currently logged in admin user can run reports
     *
     * @return bool
     *
     * @author Lee Saferite <lee.saferite@aoe.com>
     */
    public function getAllowRun()
    {
        return $this->getAdminSession()->isAllowed('report/cleansql/run');
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
