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
        $options    = false;
        $clickable  = $config->getClickable();
        $hidden     = $config->getHidden();
        $items      = $collection->getItems();

        if (count($items)) {
            $item = reset($items);
            foreach ($item->getData() as $key => $val) {
                $isFilterable = false;
                if (isset($filterable[$key])) {
                    $isFilterable = $filterable[$key];
                    if (is_array($isFilterable)) {
                        if (isset($isFilterable['type']) && $isFilterable['type'] === 'adminhtml/widget_grid_column_filter_select') {
                            $options = $this->_getFilterableOptions($isFilterable);
                            $isFilterable = $options ? $isFilterable['type'] : 'adminhtml/widget_grid_column_filter_text';
                        }
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
                }
                $this->addColumn(
                    Mage::getModel('catalog/product')->formatUrlKey($key),
                    array(
                        'header'   => Mage::helper('core')->__($label),
                        'index'    => $key,
                        'filter'   => $isFilterable,
                        'options'  => $options,
                        'sortable' => true,
                        'renderer' => $isClickable,
                        'column_css_class' => ($isHidden ? 'no-display' : ''),
                        'header_css_class' => ($isHidden ? 'no-display' : ''),
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
}
