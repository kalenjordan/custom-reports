<?php

class Clean_SqlReports_SqlReports_ChartController extends Mage_Adminhtml_Controller_Action
{
    protected $_chart;

    public function preDispatch()
    {
        parent::preDispatch();

        $this->_title($this->__("Special Charts"));
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
        $chart = $this->getChart();
        if (!$chart->getId() && $this->getRequest()->getBeforeForwardInfo('action_name') !== 'new') {
            $this->_forward('noroute');
            return;
        }

        $this->_title($this->__("Edit: %s", $chart->getTitle()));

        $this->loadLayout();
        $this->renderLayout();
    }

    public function saveAction()
    {
        $chart = $this->getChart();
        $postData = $this->getRequest()->getParams();

        $chart->addData($postData['chart']);
        if(!$chart->getReportId()) {
            $chart->setReportId(new Zend_Db_Expr('NULL'));
        }
        $chart->save();

        $this->_getSession()->addSuccess($this->__("Saved chart: %s", $chart->getTitle()));

        $this->_redirect('*/*');

        return $this;
    }

    public function deleteAction()
    {
        $chart = $this->getChart();
        if (!$chart->getId()) {
            $this->_forward('noroute');
            return $this;
        }

        $chart->delete();

        $this->_getSession()->addSuccess($this->__("Deleted chart: %s", $chart->getTitle()));

        $this->_redirect('*/*');

        return $this;
    }

    public function viewAction()
    {
        $chart = $this->getChart();
        if (!$chart->getId()) {
            $this->_forward('noroute');
            return;
        }

        $this->_title($chart->getTitle());
//        $this->_title($result->getCreatedAt());

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
        $chart = $this->getChart();
        if (!$chart->getId()) {
            $this->_forward('noroute');
            return;
        }

        $result = $this->getResult();
        try {
            if ($result) {
                $collection = $chart->getChartCollection($result);
            } else {
                $collection = $chart->getChartCollection();
            }

            if ($chart->getOutputType() == Clean_SqlReports_Model_Config_OutputType::TYPE_CALENDAR_CHART) {
                $json = $this->toCalendarJson($collection);
            } else {
                $json = $this->toReportJson($collection);
            }

            $this->getResponse()->setBody($json);
            $this->getResponse()->setHeader('Content-type', 'application/json');
        } catch (Exception $e) {
            $this->getResponse()->setBody(json_encode(array('error' => $e->getMessage())));
            $this->getResponse()->setHeader('Content-type', 'application/json');
        }
    }

    /**
     * @return Clean_SqlReports_Model_Chart
     */
    protected function getChart()
    {
        if (isset($this->_chart)) {
            return $this->_chart;
        }

        /** @var Clean_SqlReports_Model_Chart $chart */
        $chart = Mage::getModel('cleansql/chart');
        if ($this->getRequest()->getParam('id')) {
            $chart->load($this->getRequest()->getParam('id'));
        }

        $this->_chart = $chart;
        $this->getHelper()->setCurrentChart($chart);

        return $this->_chart;
    }

    /**
     * @return Clean_SqlReports_Model_Result
     */
    protected function getResult()
    {
        if (isset($this->_result)) {
            return $this->_result;
        }

        $chart = $this->getChart();
        if (!$chart->getReportId()) {
            return null;
        }

        /** @var Clean_SqlReports_Model_Result $result */
        $result = Mage::getModel('cleansql/result');
        if ($this->getRequest()->getParam('result_id')) {
            $result->load($this->getRequest()->getParam('result_id'));
            if (!$result->getId() || $result->getReportId() != $chart->getReportId()) {
                return null;
            }
        } else {
            $result = $chart->getReport()->getLatestResult();
        }

        $this->_result = $result;
        $this->getHelper()->setCurrentResult($result);

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
        switch ($this->getRequest()->getActionName()) {
            case 'index':
            case 'view':
            case 'json':
                return $this->getHelper()->getAllowView();
                break;
            case 'new':
            case 'edit':
            case 'save':
            case 'delete':
                return $this->getHelper()->getAllowEdit();
                break;
            default:
                return false;
        }
    }


    // There should be a collection model specific to the pie chart type which
    // has this specific json handling.  We should map a dropdown type field
    // on the report definition to map to this.
    public function toReportJson(Varien_Data_Collection_Db $collection)
    {
        $results = array();

        $first = true;

        /** @var $item Varien_Object */
        foreach ($collection as $item) {
            if ($first) {
                $labels = array();
                foreach ($item->getData() as $key => $value) {
                    $labels[] = $key;
                }
                $results[] = $labels;
                $first = false;
            }
            $row = array();
            foreach ($item->getData() as $key => $value) {
                if (is_numeric($value)) {
                    $value = (float)$value;
                }
                $row[] = $value;
            }

            $results[] = $row;
        }

        $jsonEncoded = json_encode($results);
        return $jsonEncoded;
    }

    public function toCalendarJson(Varien_Data_Collection_Db $collection)
    {
        $results = array();
        foreach ($collection as $item) {
            $row = array();
            foreach ($item->getData() as $key => $value) {
                if (is_numeric($value) && $value < 1000000) {
                    $value = (float)$value;
                }
                $row[] = $value;
            }
            $results[] = $row;
        }

        $jsonEncoded = json_encode($results);
        return $jsonEncoded;
    }
}
