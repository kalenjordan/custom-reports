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
            array('id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post')
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
        return Mage::registry('current_report');
    }

    protected function _addBaseFieldset()
    {
        $fieldset = $this->getForm()->addFieldset('base_fieldset', array(
            'legend'    => Mage::helper('core')->__('General'),
        ));

        $fieldset->addField('report_id', 'hidden', array(
            'name'      => 'report_id',
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
            'style'     => 'width: 640px; height: 480px;'

        ));
    }
}