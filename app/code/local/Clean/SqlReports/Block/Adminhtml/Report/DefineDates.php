<?php

class Clean_SqlReports_Block_Adminhtml_Report_DefineDates extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_report';
        $this->_blockGroup = 'cleansql';

        $this->_headerText = $this->__('Define Date Range');

        parent::__construct();

        $this->_removeButton('reset');
        $this->_removeButton('delete');
        $this->_updateButton('save', 'label', $this->__('Run'));
    }

    protected function _prepareLayout()
    {
        return Mage_Adminhtml_Block_Widget_Container::_prepareLayout();
    }
}
