<?php

class Clean_SqlReports_Block_Adminhtml_Result extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_headerText = sprintf('Report: %s<br/>Run: %s',$this->getResult()->getReport()->getTitle(), $this->getResult()->getCreatedAt());

        if ($this->getResult()->hasStartDate() || $this->getResult()->hasEndDate()) {
            $this->_headerText .= sprintf(
                '</br>Period: %s to %s',
                $this->getResult()->getStartDate(),
                $this->getResult()->getEndDate()
            );
        }

        parent::__construct();

        $this->_removeButton('add');
        $this->_removeButton('search');
        $this->_addButton('back', array(
            'label'     => $this->getBackButtonLabel(),
            'onclick'   => 'setLocation(\'' . $this->getUrl('*/sqlReports_report/') .'\')',
            'class'     => 'back',
        ));
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
