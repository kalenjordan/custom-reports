<?php

class Clean_SqlReports_Block_Adminhtml_Chart_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('chart_edit_form');
    }

    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(
            array(
                'id'     => 'edit_form',
                'action' => $this->getUrl('*/*/save', array('id' => $this->getChart()->getId())),
                'method' => 'post'
            )
        );

        $form->setData('use_container', true);
        $this->setForm($form);

        $fieldset = $this->getForm()->addFieldset(
            'base_fieldset',
            array(
                'legend' => Mage::helper('core')->__('General'),
            )
        );

        $fieldset->addField(
            'title',
            'text',
            array(
                'name'     => 'chart[title]',
                'label'    => Mage::helper('core')->__('Title'),
                'required' => true,
            )
        );

        $reports = Mage::getModel('cleansql/report')->getCollection()->toOptionArray();

        array_unshift($reports, array('value' => null, 'label' => $this->__('-- None --')));

        $fieldset->addField(
            'report_id',
            'select',
            array(
                'name'     => 'chart[report_id]',
                'label'    => Mage::helper('core')->__('Report'),
                'values'   => $reports,
                'required' => false,
            )
        );

        $fieldset->addField(
            'sql_query',
            'textarea',
            array(
                'name'     => 'chart[sql_query]',
                'label'    => Mage::helper('core')->__('SQL'),
                'required' => true,
                'style'    => 'width: 640px; height: 200;'
            )
        );

        $fieldset->addField(
            'output_type',
            'select',
            array(
                'name'     => 'chart[output_type]',
                'label'    => Mage::helper('core')->__('Output Type'),
                'values'   => Mage::getModel('cleansql/config_outputType')->toOptionArray(),
                'required' => true,
            )
        );

        $fieldset->addField(
            'chart_config',
            'textarea',
            array(
                'name'  => 'chart[chart_config]',
                'label' => Mage::helper('core')->__('Chart Configuration'),
                'style' => 'width: 640px; height: 200px;'
            )
        );

        $form->setValues($this->getChart()->getData());

        return parent::_prepareForm();
    }

    /**
     * @return Clean_SqlReports_Model_Chart
     *
     * @author Lee Saferite <lee.saferite@aoe.com>
     */
    public function getChart()
    {
        if (parent::getChart()) {
            return parent::getChart();
        }

        if ($this->getParentBlock() instanceof Varien_Object) {
            return $this->getParentBlock()->getChart();
        }

        return null;
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
