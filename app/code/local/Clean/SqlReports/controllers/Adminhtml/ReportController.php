<?php

class Clean_SqlReports_Adminhtml_ReportController extends Mage_Adminhtml_Controller_Action
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
        $report = $this->_getReport();
        if (!$report->getId() && $this->getRequest()->getBeforeForwardInfo('action_name') !== 'new') {
            $this->_forward('noroute');
            return;
        }

        Mage::register('current_report', $report);

        $this->_title($this->__("Edit: %s", $report->getTitle()));

        $this->loadLayout();
        $this->renderLayout();
    }

    public function viewtableAction()
    {
        $report = $this->_getReport();
        if (!$report->getId()) {
            $this->_forward('noroute');
            return;
        }

        Mage::register('current_report', $report);

        $this->_title($report->getTitle());

        $result = $this->_getReport()->run();

        $this->loadLayout();
        $this->renderLayout();
    }

    public function viewchartAction()
    {
        $report = $this->_getReport();
        if (!$report->getId()) {
            $this->_forward('noroute');
        }

        Mage::register('current_report', $report);

        $this->_title($report->getTitle());

        $this->loadLayout();
        $this->renderLayout();
    }

    public function runAction()
    {
        Mage::register('current_report', $this->_getReport());
        $result = $this->_getReport()->run();

        $this->_redirect('*/*/view', array('_current' => true));
    }

    public function saveAction()
    {
        $report = $this->_getReport();
        $postData = $this->getRequest()->getParams();

        $report->addData($postData['report']);
        $report->save();

        Mage::getSingleton('adminhtml/session')->addSuccess($this->__("Saved report: %s", $report->getTitle()));

        $this->_redirect('*/*');

        return $this;
    }

    public function deleteAction()
    {
        $report = $this->_getReport();
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

    public function resultAction()
    {
        $result = $this->_getResult();
        if (!$result->getId()) {
            $this->_forward('noroute');
            return;
        }

        Mage::register('current_result', $result);
        Mage::register('current_report', $result->getReport());

        $this->_title($this->_getReport()->getTitle());
        $this->_title($result->getCreatedAt());

        $this->loadLayout();
        $this->renderLayout();
    }

    public function deleteResultAction()
    {
        $result = $this->_getResult();
        if (!$result->getId()) {
            Mage::getSingleton('adminhtml/session')->addSuccess($this->__("Unable to find the report result"));
            $this->_redirect('*/*');
            return $this;
        }

        $result->delete();

        Mage::getSingleton('adminhtml/session')->addSuccess($this->__("Deleted report result: %s / %s", $result->getReport()->getTitle(), $result->getCreatedAt()));

        $this->_redirect('*/*/view', array('id' => $result->getReportId()));

        return $this;
    }

    /**
     * Export grid to CSV format
     */
    public function exportCsvAction()
    {
        $result = $this->_getResult();
        if (!$result->getId()) {
            $this->_forward('noroute');
            return;
        }

        Mage::register('current_result', $result);
        Mage::register('current_report', $result->getReport());

        $this->loadLayout();

        /** @var $grid Mage_Adminhtml_Block_Widget_Grid */
        $grid = $this->getLayout()->getBlock('report.view.grid');
        if (!$grid instanceof Mage_Adminhtml_Block_Widget_Grid) {
            $this->_forward('noroute');
            return;
        }

        $fileName = strtolower(str_replace(' ', '_', $this->_getReport()->getTitle())) . '_' . time() . '.csv';

        $this->_prepareDownloadResponse(
            $fileName,
            $grid->getCsvFile(),
            'text/csv'
        );
    }

    /**
     * Get JSON action
     *
     * @return void
     */
    public function getJsonAction() {
        try {
            $report = $this->_getReport();

            if ($report->getOutputType() == Clean_SqlReports_Model_Config_OutputType::TYPE_CALENDAR_CHART) {
                $json = $report->getReportCollection()->toCalendarJson();
            } else {
                $json = $report->getReportCollection()->toReportJson();
            }
            $this->getResponse()->setBody($json);
            $this->getResponse()->setHeader('Content-type', 'application/json');
        } catch (Exception $e) {
            $this->getResponse()->setBody(json_encode(array('error' => $e->getMessage())));
            $this->getResponse()->setHeader('Content-type', 'application/json');
        }
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
        if ($this->getRequest()->getParam('id')) {
            $report->load($this->getRequest()->getParam('id'));
        }

        $this->_report = $report;
        return $this->_report;
    }

    /**
     * @return Clean_SqlReports_Model_Result
     */
    protected function _getResult()
    {
        if (isset($this->_result)) {
            return $this->_result;
        }

        $result = Mage::getModel('cleansql/result');
        if ($this->getRequest()->getParam('id')) {
            $result->load($this->getRequest()->getParam('id'));
        }

        $this->_result = $result;
        return $this->_result;
    }

    protected function _isAllowed()
    {
        /** @var Clean_SqlReports_Helper_Data $helper */
        $helper = Mage::helper('cleansql');

        switch ($this->getRequest()->getActionName()) {
            case 'index':
            case 'view':
            case 'result':
                return $helper->getAllowView();
                break;
            case 'new':
            case 'edit':
            case 'save':
            case 'delete':
                return $helper->getAllowEdit();
                break;
            case 'run':
            case 'deleteResult':
                return $helper->getAllowRun();
                break;
            default:
                return false;
        }
    }
}
