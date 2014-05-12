<?php

class Clean_SqlReports_SqlReports_ResultController extends Mage_Adminhtml_Controller_Action
{
    protected $_report;

    public function preDispatch()
    {
        parent::preDispatch();

        $this->_title($this->__("Special Reports"));
    }

    public function viewAction()
    {
        $result = $this->getResult();
        if (!$result->getId()) {
            $this->_forward('noroute');
            return;
        }

        $this->_title($result->getReport()->getTitle());
        $this->_title($result->getCreatedAt());

        $this->loadLayout();
        $this->renderLayout();
    }

    public function deleteAction()
    {
        $result = $this->getResult();
        if (!$result->getId()) {
            $this->_forward('noroute');
            return;
        }

        $result->delete();

        Mage::getSingleton('adminhtml/session')->addSuccess($this->__("Deleted report result: %s / %s", $result->getReport()->getTitle(), $result->getCreatedAt()));

        $this->_redirect('*/sqlReports_report/view', array('id' => $result->getReportId()));

        return $this;
    }

    /**
     * Export grid to CSV format
     */
    public function exportCsvAction()
    {
        $result = $this->getResult();
        if (!$result->getId()) {
            $this->_forward('noroute');
            return;
        }

        $this->loadLayout();

        /** @var $grid Mage_Adminhtml_Block_Widget_Grid */
        $grid = $this->getLayout()->getBlock('sqlreports.result.view.grid');
        if (!$grid instanceof Mage_Adminhtml_Block_Widget_Grid) {
            $this->_getSession()->addError($this->__('Could not load correct block'));
            $this->_redirect('*/*/view', array('_current' => true));
            return;
        }

        $fileName = strtolower(str_replace(' ', '_', $result->getReport()->getTitle())) . '_' . time() . '.csv';

        $this->_prepareDownloadResponse(
            $fileName,
            $grid->getCsvFile(),
            'text/csv'
        );
    }

    /**
     * @return Clean_SqlReports_Model_Result
     */
    protected function getResult()
    {
        if (isset($this->_result)) {
            return $this->_result;
        }

        /** @var Clean_SqlReports_Model_Result $result */
        $result = Mage::getModel('cleansql/result');
        if ($this->getRequest()->getParam('id')) {
            $result->load($this->getRequest()->getParam('id'));
        }

        $this->_result = $result;
        $this->getHelper()->setCurrentResult($result);
        $this->getHelper()->setCurrentReport($result->getReport());

        return $this->_result;
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
        /** @var Clean_SqlReports_Helper_Data $helper */
        $helper = Mage::helper('cleansql');

        switch ($this->getRequest()->getActionName()) {
            case 'view':
            case 'exportCsv':
                return $helper->getAllowView();
                break;
            case 'delete':
                return $helper->getAllowRun();
                break;
            default:
                return false;
        }
    }
}
