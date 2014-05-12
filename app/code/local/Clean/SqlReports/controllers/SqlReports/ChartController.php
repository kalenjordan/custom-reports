<?php

class Clean_SqlReports_SqlReports_ChartController extends Mage_Adminhtml_Controller_Action
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

    /**
     * Get JSON action
     *
     * @return void
     */
    public function jsonAction()
    {
        $result = $this->getResult();
        if (!$result->getId()) {
            $this->_forward('noroute');
            return;
        }

        try {
            if ($result->getReport()->getOutputType() == Clean_SqlReports_Model_Config_OutputType::TYPE_CALENDAR_CHART) {
                $json = $result->getResultCollection()->toCalendarJson();
            } else {
                $json = $result->getResultCollection()->toReportJson();
            }
            $this->getResponse()->setBody($json);
            $this->getResponse()->setHeader('Content-type', 'application/json');
        } catch (Exception $e) {
            $this->getResponse()->setBody(json_encode(array('error' => $e->getMessage())));
            $this->getResponse()->setHeader('Content-type', 'application/json');
        }
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
        return $helper->getAllowView();
    }
}
