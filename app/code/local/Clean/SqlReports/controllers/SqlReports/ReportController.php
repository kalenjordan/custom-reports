<?php

class Clean_SqlReports_SqlReports_ReportController extends Mage_Adminhtml_Controller_Action
{
    protected $_report;

    public function preDispatch()
    {
        parent::preDispatch();

        $this->_title($this->__("Special Reports"));
    }

    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function editAction()
    {
        $report = $this->getReport();
        if (!$report->getId() && $this->getRequest()->getBeforeForwardInfo('action_name') !== 'new') {
            $this->_forward('noroute');
            return;
        }

        $this->_title($this->__("Edit: %s", $report->getTitle()));

        $this->loadLayout();
        $this->renderLayout();
    }

    public function viewAction()
    {
        $report = $this->getReport();
        if (!$report->getId()) {
            $this->_forward('noroute');
            return;
        }

        $this->_title($report->getTitle());

        $this->loadLayout();
        $this->renderLayout();
    }

    public function runAction()
    {
        $report = $this->getReport();
        if (!$report->getId()) {
            $this->_forward('noroute');
            return;
        }

        if ($this->needsDatesDefined()) {
            $this->_getSession()->addNotice($this->__('Please specify the reporting period'));
            $this->_redirect('*/*/defineDates', array('id' => $report->getId()));
            return;
        }

        $result = $this->getReport()->run($this->getRequest()->getParams());

        $this->_redirect('*/sqlReports_result/view', array('id' => $result->getId()));
    }

    public function defineDatesAction()
    {
        $report = $this->getReport();
        if (!$report->getId()) {
            $this->_forward('noroute');
            return;
        }

        $this->_title($this->__("Define Dates: %s", $report->getTitle()));

        $this->loadLayout();
        $this->renderLayout();
    }

    public function saveAction()
    {
        $report = $this->getReport();
        $postData = $this->getRequest()->getParams();

        $report->addData($postData['report']);
        $report->save();

        Mage::getSingleton('adminhtml/session')->addSuccess($this->__("Saved report: %s", $report->getTitle()));

        $this->_redirect('*/*');

        return $this;
    }

    public function deleteAction()
    {
        $report = $this->getReport();
        if (!$report->getId()) {
            Mage::getSingleton('adminhtml/session')->addSuccess($this->__("Unable to find the report"));
            $this->_redirect('*/*');
            return $this;
        }

        $report->delete();

        Mage::getSingleton('adminhtml/session')->addSuccess($this->__("Deleted report: %s", $report->getTitle()));

        $this->_redirect('*/*');

        return $this;
    }

    /**
     * @return bool
     */
    protected function needsDatesDefined()
    {
        if ($this->getReport()->hasReportingPeriod()
            && (!$this->getRequest()->getParam('start_date') || !$this->getRequest()->getParam('end_date'))
        ) {
            return true;
        }
        return false;
    }

    /**
     * @return Clean_SqlReports_Model_Report
     */
    protected function getReport()
    {
        if (isset($this->_report)) {
            return $this->_report;
        }

        /** @var Clean_SqlReports_Model_Report $report */
        $report = Mage::getModel('cleansql/report');
        if ($this->getRequest()->getParam('id')) {
            $report->load($this->getRequest()->getParam('id'));
        }

        $this->_report = $report;
        $this->getHelper()->setCurrentReport($report);

        return $this->_report;
    }

    /**
     * @return Clean_SqlReports_Helper_Data
     *
     * @author Lee Saferite <lee.saferite@aoe.com>
     */
    protected function getHelper()
    {
        return Mage::helper('cleansql');
    }

    protected function _isAllowed()
    {
        switch ($this->getRequest()->getActionName()) {
            case 'index':
            case 'view':
                return $this->getHelper()->getAllowView();
                break;
            case 'new':
            case 'edit':
            case 'save':
            case 'delete':
                return $this->getHelper()->getAllowEdit();
                break;
            case 'run':
            case 'defineDates':
                return $this->getHelper()->getAllowRun();
                break;
            default:
                return false;
        }
    }
}
