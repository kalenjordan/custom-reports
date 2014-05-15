<?php

class Clean_SqlReports_Block_Adminhtml_Report_View_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    protected $_sqlQueryResults;

    public function __construct()
    {
        parent::__construct();
        $this->setId('reportsGrid');
        $this->setDefaultSort('report_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->addExportType('*/*/exportCsv', $this->__('CSV'));
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->unsetChild('search_button');
        $this->unsetChild('reset_filter_button');

        return $this;
    }

    /**
     * @return Clean_SqlReports_Model_Report
     */
    protected function _getReport()
    {
        return Mage::registry('current_report');
    }

    /**
     * @author Lee Saferite <lee.saferite@aoe.com>
     * @return Varien_Data_Collection_Db
     */
    protected function _createCollection()
    {
        return $this->_getReport()->getReportCollection();
    }

    protected function _prepareCollection()
    {
        if (isset($this->_collection)) {
            return $this->_collection;
        }

        $collection = $this->_createCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $collection = $this->_createCollection();
        $collection->setPageSize(1);
        $collection->load();

        $items = $collection->getItems();
        if (count($items)) {
            $item = reset($items);
            foreach ($item->getData() as $key => $val) {
                $this->addColumn(
                    $key,
                    array(
                        'header'   => Mage::helper('core')->__($key),
                        'index'    => $key,
                        'filter'   => false,
                        'sortable' => true,
                    )
                );
            }
        }

        return parent::_prepareColumns();
    }
}
