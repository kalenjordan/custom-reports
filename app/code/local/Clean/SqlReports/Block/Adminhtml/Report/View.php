<?php

class Clean_SqlReports_Block_Adminhtml_Report_View extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->_headerText = $this->getReport()->getTitle();
        $this->_addButtonLabel = $this->__('Run');

        parent::__construct();

        $this->_removeButton('search');
    }

    public function getCreateUrl()
    {
        return $this->getUrl('*/*/run', array('_current' => true));
    }

    protected function _prepareLayout()
    {
        return Mage_Adminhtml_Block_Widget_Container::_prepareLayout();
    }

    /**
     * @return Clean_SqlReports_Model_Report
     */
    protected function getReport()
    {
        return $this->_getHelper()->getCurrentReport();
    }

    /**
     * @return Clean_SqlReports_Helper_Data
     *
     * @author Lee Saferite <lee.saferite@aoe.com>
     */
    protected function _getHelper()
    {
        return Mage::helper('cleansql');
    }
}
