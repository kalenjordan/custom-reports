<?php

class Clean_SqlReports_Adminhtml_CustomreportController extends Mage_Adminhtml_Controller_Action
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
        Mage::register('current_report', $this->_getReport());
        $this->_title($this->__("Edit: %s", $this->_getReport()->getTitle()));

        $this->loadLayout();
        $this->renderLayout();
    }

    public function viewtableAction()
    {
        Mage::register('current_report', $this->_getReport());
        $this->_title($this->_getReport()->getTitle());

        $this->loadLayout();
        $this->renderLayout();
    }

    public function viewchartAction()
    {
        Mage::register('current_report', $this->_getReport());
        $this->_title($this->_getReport()->getTitle());

        $this->loadLayout();
        $this->renderLayout();
    }

    public function saveAction()
    {
        $report = $this->_getReport();
        $postData = $this->getRequest()->getParams();

        $report->addData($postData['report']);
        $report->save();

        if (!$postData['report_id']) {
            Mage::app()->cleanCache(array('BACKEND_MAINMENU'));
        }

        if ($this->getRequest()->getParam('back')) {
            $this->_redirect('*/*/edit', array('report_id' => $report->getId()));
            return $this;
        }

        $this->_redirect('*/*');

        return $this;
    }

    public function deleteAction()
    {
        $report = $this->_getReport();
        if (!$report->getId()) {
            Mage::getSingleton('adminhtml/session')->addSuccess($this->__("Wasn't able to find the report"));
            $this->_redirect('adminhtml/adminhtml_customreport');
            return $this;
        }

        $report->delete();

        Mage::app()->cleanCache(array('BACKEND_MAINMENU'));
        Mage::getSingleton('adminhtml/session')->addSuccess($this->__("Deleted report: %s", $report->getTitle()));

        $this->_redirect('*/*');

        return $this;
    }

    /**
     * Export grid to CSV format
     */
    public function exportCsvAction()
    {
        Mage::register('current_report', $this->_getReport());
        $this->loadLayout();

        /** @var $grid Mage_Adminhtml_Block_Widget_Grid */
        $grid = $this->getLayout()->getBlock('report.view.grid');
        if(!$grid instanceof Mage_Adminhtml_Block_Widget_Grid) {
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
     * Export grid to Excel format
     */
    public function exportExcelAction()
    {
        if ($this->_isPhpExcelAvailable()) {
            $this->_exportToXlsx();
        }
        else {
            $this->_exportToXls();
        }
    }

    /**
     * Export grid to Excel XLS format
     */
    protected function _exportToXls()
    {
        Mage::register('current_report', $this->_getReport());
        $this->loadLayout();

        /** @var $grid Mage_Adminhtml_Block_Widget_Grid */
        $grid = $this->getLayout()->getBlock('report.view.grid');

        if(!$grid instanceof Mage_Adminhtml_Block_Widget_Grid) {
            $this->_forward('noroute');
            return;
        }

        $fileName = strtolower(str_replace(' ', '_', $this->_getReport()->getTitle())) . '_' . time() . '.xls';
        $content  = $grid->getExcel($fileName);

        $this->_prepareDownloadResponse(
            $fileName,
            $content
        );
    }

    /**
     * Export grid to Excel XLSX format
     *
     * Requires an external library:
     *      - Download library from https://github.com/PHPOffice/PHPExcel
     *      - Create the folder 'lib/PHPExcel'
     *      - Copy the downloaded 'Classes' folder to 'lib/PHPExcel'
     */
    protected function _exportToXlsx()
    {
        Mage::register('current_report', $this->_getReport());
        $this->loadLayout();

        /** @var $grid Mage_Adminhtml_Block_Widget_Grid */
        $grid = $this->getLayout()->getBlock('report.view.grid');

        if(!$grid instanceof Mage_Adminhtml_Block_Widget_Grid) {
            $this->_forward('noroute');
            return;
        }

        $config     = $this->_getReport()->getGridConfig();
        $alignment  = $config->getAlignment();

        $fileName = strtolower(str_replace(' ', '_', $this->_getReport()->getTitle())) . '_' . time() . '.xlsx';
        $worksheet_name = $this->_getReport()->getTitle();
        $content  = $grid->getExcel2007Data();

        include $this->_getPhpExcelPath();
        $objPHPExcel = new PHPExcel();

        $rowCount = 1;
        $maxCol = 0;
        $column_alignment = array();
        foreach($content as $value){
            $column = 'A';
            $maxCol = 0;
            foreach($value as $val){

                if ($rowCount == 1) {
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($column.$rowCount, $val['label']);

                    if(isset($alignment[$val['key']])) {
                        $column_alignment[] = array('column' => $column, 'alignment' => $alignment[$val['key']]);
                    }
                }
                else {
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($column.$rowCount, $val);
                }

                $column++;
                $maxCol++;
            }

            $rowCount++;
        }

        // Columns alignment
        foreach($column_alignment as $value){
            $column = $value['column'];
            $cell_alignment = $value['alignment'];
            if($cell_alignment == 'right') {
                $objPHPExcel->getActiveSheet()->getStyle($column.'1:'.$column.$rowCount)
                     ->getAlignment()
                     ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            }
            elseif($cell_alignment == 'center') {
                $objPHPExcel->getActiveSheet()->getStyle($column.'1:'.$column.$rowCount)
                     ->getAlignment()
                     ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            }
        }

        // Auto-size width
        for($column_index = 0; $column_index <= $maxCol; $column_index++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex($column_index))->setAutoSize(true);
        }

        $objPHPExcel->getActiveSheet()->setTitle($worksheet_name);
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=$fileName");
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }

    /**
     * Check if library PHPExcel is available.
     *
     * @return boolean
     */
    protected function _isPhpExcelAvailable()
    {
        return file_exists($this->_getPhpExcelPath());
    }

    /**
     * Get the PHPExcel library path.
     *
     * @return string
     */
    protected function _getPhpExcelPath()
    {
        return Mage::getBaseDir("lib") . DS . "PHPExcel" . DS . "Classes" . DS ."PHPExcel.php";
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
            $this->getResponse()->setHeader('Content-type', 'application/json', true);
        } catch (Exception $e) {
            $this->getResponse()->setBody(json_encode(array('error' => $e->getMessage())));
            $this->getResponse()->setHeader('Content-type', 'application/json', true);
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
        if ($this->getRequest()->getParam('report_id')) {
            $report->load($this->getRequest()->getParam('report_id'));
        }

        $this->_report = $report;
        return $this->_report;
    }

    protected function _isAllowed()
    {
        $isView = in_array($this->getRequest()->getActionName(), array('index', 'view', 'viewtable', 'viewchart', 'getJson', 'exportCsv', 'exportExcel'));

        /** @var $helper Clean_SqlReport_Helper_Data */
        $helper = Mage::helper('cleansql');

        return ($isView ? $helper->getAllowView() : $helper->getAllowEdit());
    }
}
