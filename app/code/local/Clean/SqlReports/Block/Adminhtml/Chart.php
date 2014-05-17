<?php

class Clean_SqlReports_Block_Adminhtml_Chart extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->_headerText = Mage::helper('core')->__('Manage Charts');
        $this->_addButtonLabel = Mage::helper('core')->__('Add Chart');

        parent::__construct();
    }

    protected function _prepareLayout()
    {
        return Mage_Adminhtml_Block_Widget_Container::_prepareLayout();
    }
}
