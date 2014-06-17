<?php

class Clean_SqlReports_Block_Adminhtml_Report_DefineDates_Form extends Mage_Adminhtml_Block_Widget_Form
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('report_defineDates_form');
    }

    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(
            array('id' => 'edit_form', 'action' => $this->getUrl('*/*/run', array('id' => $this->_getReport()->getId())), 'method' => 'post')
        );

        $form->setData('use_container', true);
        $this->setForm($form);

        $this->_addBaseFieldset();

        $form->setValues($this->_getReport()->getData());

        return parent::_prepareForm();
    }

    /**
     * @return Clean_SqlReports_Model_Report
     */
    protected function _getReport()
    {
        return $this->_getHelper()->getCurrentReport();
    }

    /**
     * @return Clean_SqlReports_Helper_Data
     */
    protected function _getHelper()
    {
        return Mage::helper('cleansql');
    }

    protected function _addBaseFieldset()
    {
        $fieldset = $this->getForm()->addFieldset('base_fieldset', array(
            'legend'    => Mage::helper('core')->__('Dates'),
        ));

        $fieldset->addField('start_date', 'date', array(
            'name'      => 'start_date',
            'label'     => $this->_getHelper()->__('Start Date'),
            'image'     => $this->getSkinUrl('images/grid-cal.gif'),
            'format'    => Varien_Date::DATE_INTERNAL_FORMAT,
        ));

        $fieldset->addField('end_date', 'date', array(
            'name'      => 'end_date',
            'label'     => $this->_getHelper()->__('End Date'),
            'image'     => $this->getSkinUrl('images/grid-cal.gif'),
            'format'    => Varien_Date::DATE_INTERNAL_FORMAT,
        ));
    }
}
