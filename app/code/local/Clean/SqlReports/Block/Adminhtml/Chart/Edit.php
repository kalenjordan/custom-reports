<?php
/**
 * @method Clean_SqlReports_Model_Chart getChart()
 * @method Clean_SqlReports_Block_Adminhtml_Chart_Edit_Form setChart(Clean_SqlReports_Model_Chart $chart)
 */
class Clean_SqlReports_Block_Adminhtml_Chart_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        $this->_headerText = $this->__('Edit Chart');

        parent::__construct();

        $this->_addDeleteButton();

        $this->_removeButton('reset');
    }

    protected function _addDeleteButton()
    {
        $deleteUrl = $this->getDeleteUrl();
        $confirmText = Mage::helper('adminhtml')->__('Sure you want to delete this?');
        $this->_addButton('delete', array(
            'label'     => 'Delete',
            'onclick'   => "deleteConfirm('$confirmText', '$deleteUrl');",
            'class'     => 'save',
        ));
    }

    protected function _prepareLayout()
    {
        return Mage_Adminhtml_Block_Widget_Container::_prepareLayout();
    }
}
