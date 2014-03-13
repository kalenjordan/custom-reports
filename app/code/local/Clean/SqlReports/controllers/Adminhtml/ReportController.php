<?php

class Clean_SqlReports_Adminhtml_ReportController extends Mage_Adminhtml_Controller_Action
{
    protected $_report;

    protected function _initAction()
    {
        $this->_title($this->__("Reports"));

        $this->loadLayout()
            ->_setActiveMenu('report/cleansql');

        return $this;
    }

    public function indexAction()
    {
        $this->_initAction();
        $this->_title("Manage Reports");
        $this->renderLayout();
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function editAction()
    {
        Mage::register('current_report', $this->_getReport());
        $this->_initAction();
        $this->_title("Edit: " . $this->_getReport()->getTitle());
        $this->renderLayout();
    }

    public function viewAction()
    {
        Mage::register('current_report', $this->_getReport());
        $this->_initAction();
        $this->_title($this->_getReport()->getTitle());
        $this->renderLayout();
    }

    public function saveAction()
    {
        $report = $this->_getReport();
        $postData = $this->getRequest()->getParams();

        foreach ($postData['report'] as $key => $value) {
            $report->setData($key, $value);
        }

        $report->save();
        Mage::getSingleton('adminhtml/session')->addSuccess("Saved report: " . $report->getTitle());
        $this->_redirect('admin_cleansql/adminhtml_report');

        return $this;
    }

    public function deleteAction()
    {
        $report = $this->_getReport();
        if (!$report->getId()) {
            Mage::getSingleton('adminhtml/session')->addSuccess("Wasn't able to find the report");
            $this->_redirect('admin_cleansql/adminhtml_report');
            return $this;
        }

        $report->delete();
        Mage::getSingleton('adminhtml/session')->addSuccess("Deleted report: " . $report->getTitle());
        $this->_redirect('admin_cleansql/adminhtml_report');

        return $this;
    }

    /**
     * @return Clean_SqlReports_Model_Report
     */
    protected function _getReport()
    {
        if (isset($this->_report)) {
            return $this->_report;
        }

        $report = Mage::getModel('cleansql/report');
        if ($this->getRequest()->getParam('report_id')) {
            $report->load($this->getRequest()->getParam('report_id'));
        }

        $this->_report = $report;
        return $this->_report;
    }
}