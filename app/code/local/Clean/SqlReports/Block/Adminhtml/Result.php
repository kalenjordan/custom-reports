<?php

class Clean_SqlReports_Block_Adminhtml_Result extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_headerText = $this->getResult()->getReport()->getTitle() . ' / ' . $this->getResult()->getCreatedAt();

        parent::__construct();

        $this->_removeButton('add');
        $this->_removeButton('search');
    }

    protected function _prepareLayout()
    {
        return Mage_Adminhtml_Block_Widget_Container::_prepareLayout();
    }

    /**
     * @return Clean_SqlReports_Model_Result
     */
    protected function getResult()
    {
        return $this->_getHelper()->getCurrentResult();
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
