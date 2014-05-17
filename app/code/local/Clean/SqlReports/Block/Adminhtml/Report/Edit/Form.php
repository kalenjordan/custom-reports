<?php

class Clean_SqlReports_Block_Adminhtml_Report_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('report_edit_form');
    }

    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(
            array('id' => 'edit_form', 'action' => $this->getUrl('*/*/save', array('id' => $this->_getReport()->getId())), 'method' => 'post')
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
     *
     * @author Lee Saferite <lee.saferite@aoe.com>
     */
    protected function _getHelper()
    {
        return Mage::helper('cleansql');
    }

    protected function _addBaseFieldset()
    {
        $fieldset = $this->getForm()->addFieldset('base_fieldset', array(
            'legend'    => Mage::helper('core')->__('General'),
        ));

        $fieldset->addField('title', 'text', array(
            'name'      => 'report[title]',
            'label'     => Mage::helper('core')->__('Title'),
            'required'  => true,
        ));

        $fieldset->addField('sql_query', 'textarea', array(
            'name'      => 'report[sql_query]',
            'label'     => Mage::helper('core')->__('SQL'),
            'required'  => true,
            'style'     => 'width: 640px; height: 200;'
        ));

        $fieldset->addField(
            'column_config',
            'textarea',
            array(
                'name'     => 'report[column_config]',
                'label'    => $this->__('Column Config'),
                'required' => false,
                'style'    => 'width: 640px; height: 240px;'
            )
        );

        // Start Refactor : Replace with predefined types and a source
        $fieldset->addField('output_type', 'select', array(
            'name' => 'report[output_type]',
            'label' => Mage::helper('core')->__('Output Type'),
            'values' => Mage::getModel('cleansql/config_outputType')->toOptionArray(),
            'required' => true,
        ));
        // End Refactor

        $fieldset->addField('chart_config', 'textarea', array(
            'name'      => 'report[chart_config]',
            'label'     => Mage::helper('core')->__('Chart Configuration'),
            'style'     => 'width: 640px; height: 200px;'
        ));
    }
}
