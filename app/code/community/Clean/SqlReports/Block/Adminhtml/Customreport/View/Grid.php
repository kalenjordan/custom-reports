<?php

class Clean_SqlReports_Block_Adminhtml_Customreport_View_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    protected $_sqlQueryResults;

    public function __construct()
    {
        parent::__construct();
        $this->setId('reportsGrid');
        $this->setDefaultSort('report_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->addExportType('*/*/exportCsv', $this->__('CSV'));
        $this->addExportType('*/*/exportExcel', $this->__('Excel'));
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        return $this;
    }

    /**
     * @return Clean_SqlReports_Model_Report
     */
    protected function _getReport()
    {
        return Mage::registry('current_report');
    }

    /**
     * @author Lee Saferite <lee.saferite@aoe.com>
     * @return Varien_Data_Collection_Db
     */
    protected function _createCollection()
    {
        return $this->_getReport()->getReportCollection();
    }

    /**
     * make an attempt to catch errors loading/preparing grid
     * for instance: if the query contains an `id` column which is non-unique
     * the varien data collection will throw a:
     *  "Item (Varien_Object) with the same id "1" already exist"
     *  exception
     * @see Mage_Adminhtml_Block_Widget_Grid::_prepareCollection
     */
    protected function _prepareCollection()
    {
        if (isset($this->_collection)) {
            return $this->_collection;
        }

        $collection = $this->_createCollection();
        $this->setCollection($collection);

        try {
            parent::_prepareCollection();
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('An error occured rendering the grid: ' . $e->getMessage()));
            Mage::logException($e);
            //abort rendering grid and replace collection with an empty one
            $this->setCollection(new Varien_Data_Collection());
        }
        return $this;
    }

    public function getRowUrl($row)
    {
        return null;
    }

    protected function _prepareColumns()
    {
        try {
            $collection = $this->_createCollection();
            $collection->setPageSize(1);
            $collection->load();
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('An error occured rendering the grid: ' . $e->getMessage()));
            Mage::logException($e);
            $collection = new Varien_Data_Collection();
        }

        /** @var Clean_SqlReports_Model_Report_GridConfig $config */
        $config     = $this->_getReport()->getGridConfig();

        $labels     = $config->getLabels();
        $filterable = $config->getFilterable();
        $clickable  = $config->getClickable();
        $hidden     = $config->getHidden();
        $type       = $config->getType();
        $alignment  = $config->getAlignment();
        $items      = $collection->getItems();

        $store = Mage::app()->getStore();
        $currency_code = $store->getBaseCurrency()->getCode();
        if (count($items)) {
            $item = reset($items);
            foreach ($item->getData() as $key => $val) {
                $options = FALSE;
                $column_css_class = array();
                $header_css_class = array();

                $isFilterable = false;
                if (isset($filterable[$key])) {
                    $isFilterable = $filterable[$key];
                    if (is_array($isFilterable) && isset($isFilterable['type']) && $isFilterable['type'] === 'adminhtml/widget_grid_column_filter_select') {
                        $options = $this->_getFilterableOptions($isFilterable);
                        $isFilterable = $options ? $isFilterable['type'] : 'adminhtml/widget_grid_column_filter_text';
                    } else {
                        $isFilterable = $filterable[$key];
                    }
                } elseif (in_array($key, $filterable)) {
                    $isFilterable = 'adminhtml/widget_grid_column_filter_text';
                }
                $label = $key;
                if (isset($labels[$key])) {
                    $label = $labels[$key];
                }
                $isClickable = false;
                if (isset($clickable[$key])) {
                    $isClickable = 'cleansql/adminhtml_widget_grid_column_renderer_clickable';
                }
                $isHidden = false;
                if (isset($hidden[$key])) {
                    $isHidden = true;
                    $column_css_class[] = 'no-display';
                    $header_css_class[] = 'no-display';
                }

                if(isset($alignment[$key])) {
                    if($alignment[$key] == 'right') {
                        $column_css_class[] = 'a-right';
                    }
                    elseif($alignment[$key] == 'center') {
                        $column_css_class[] = 'a-center';
                    }
                }

                $this->addColumn(
                    Mage::getModel('catalog/product')->formatUrlKey($key),
                    array(
                        'header'   => Mage::helper('core')->__($label),
                        'index'    => $key,
                        'filter'   => $isFilterable,
                        'options'  => $options,
                        'sortable' => true,
                        'type'     => (isset($type[$key]) ? $type[$key] : 'text'),
                        'renderer' => $isClickable,
                        'column_css_class' => implode(' ', $column_css_class),
                        'header_css_class' => implode(' ', $header_css_class),
                        'currency_code'    => $currency_code
                    )
                );
            }
        }

        return parent::_prepareColumns();
    }

    protected function _getFilterableOptions($isFilterable)
    {
        if (isset($isFilterable['options'])) {
            if (is_array($isFilterable['options'])) {
                return $isFilterable['options'];
            }
            return false;
        }

        if (isset($isFilterable['source_model'])) {
            $model = Mage::getModel($isFilterable['source_model']);
        } elseif (isset($isFilterable['resource_model'])) {
            $model = Mage::getResourceModel($isFilterable['resource_model']);
        }

        if ($model) {
            if (isset($isFilterable['method'])) {
                if (!method_exists($model, $isFilterable['method'])) {
                    return false;
                }

                $options = $model->{$isFilterable['method']}();
                if (is_array($options)) {
                    $fields = isset($isFilterable['option_data']) ? $isFilterable['option_data'] : false;
                    if (is_array($fields) && isset($fields['value']) && isset($fields['label'])) {
                        return $this->_toFlatArray($options, $fields['value'], $fields['label']);
                    }
                    return $this->_toFlatArray($options);
                }
                return false;
            }

            // Magento fallback
            if (method_exists($model, 'toOptionArray')) {
                return $this->_toFlatArray($model->toOptionArray());
            } elseif (method_exists($model, 'getOptionArray')) {
                return $this->_toFlatArray($model->getOptionArray());
            }
        }
        return false;
    }

    protected function _toFlatArray($options, $value = 'value', $label = 'label')
    {
        if (!is_array($options)) {
            return false;
        }

        if (!is_array(reset($options))) {
            return $options;
        }

        if (isset($options[key($options)][$value]) && isset($options[key($options)][$label])) {
            foreach ($options as $key => $option) {
                $options[$option[$value]] = $option[$label];
                unset($options[$key]);
            }
            return $options;
        }
        return false;
    }

    public function getExcel2007Data()
    {
        $this->_isExport = true;
        $this->_prepareGrid();
        $this->getCollection()->getSelect()->limit();
        $this->getCollection()->setPageSize(0);
        $this->getCollection()->load();
        $this->_afterLoadCollection();

        $data = array();
        $xl_data = array();


        /** @var Clean_SqlReports_Model_Report_GridConfig $config */
        $config = $this->_getReport()->getGridConfig();

        // Retrieve headers
        // We will need original key and the translated one (as a label).
        // 'key' will be used to access configuration options while 'label' is
        // the text that will be shown at the header
        $labels = $config->getLabels();
        $hidden = $config->getHidden();
        $items  = $this->getCollection()->getItems();
        if (count($items)) {
            $item = reset($items);
            foreach ($item->getData() as $key => $val) {
                if (!isset($hidden[$key])) {
                    $label = $key;
                    if (isset($labels[$key])) {
                        $label = $labels[$key];
                    }
                    $data[] = array('key'   => $key,
                                    'label' => Mage::helper('core')->__($label));
                }
            }
        }

        /*foreach ($this->_columns as $column) {
            if (!$column->getIsSystem()) {
                $data[] = ''.$column->getExportHeader().'';
            }
        }*/
        $xl_data[] = $data;

        foreach ($this->getCollection() as $item) {
            $data = array();
            foreach ($this->_columns as $column) {
                if (!$column->getIsSystem()) {
                    $data[] = $column->getRowFieldExport($item);
                }
            }
            $xl_data[] = $data;
        }

        if ($this->getCountTotals())
        {
            $data = array();
            foreach ($this->_columns as $column) {
                if (!$column->getIsSystem()) {
                    $data[] = $column->getRowFieldExport($this->getTotals());
                }
            }
        }
        return $xl_data;
    }

}
