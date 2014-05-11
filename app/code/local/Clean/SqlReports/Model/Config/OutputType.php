<?php
/**
 * Created by PhpStorm.
 * User: amacgregor
 * Date: 11/05/14
 * Time: 8:33 AM
 */

class Clean_SqlReports_Model_Config_OutputType extends Mage_Core_Model_Config_Data
{
    // arrayToDatatable Group
    const TYPE_PIE_CHART            = 'PieChart';
    const TYPE_BAR_CHART            = 'BarChart';
    const TYPE_GEO_CHART            = 'GeoChart';
    const TYPE_AREA_CHART           = 'AreaChart';
    const TYPE_LINE_CHART           = 'LineChart';
    const TYPE_COMBO_CHART          = 'ComboChart';
    const TYPE_BUBBLE_CHART         = 'BubbleChart';
    const TYPE_COLUMN_CHART         = 'ColumnChart';
    const TYPE_TREEMAP_CHART        = 'TreeMap';
    const TYPE_SCATTER_CHART        = 'ScatterChart';
    const TYPE_CANDLESTICK_CHART    = 'CandleChart';
    const TYPE_STEPPEDAREA_CHART    = 'SteppedAreaChart';

    // Custom datatable group
    const TYPE_ORG_CHART        = 'OrgChart';
    const TYPE_TABLE_CHART      = 'Table';
    const TYPE_SANKEY_CHART     = 'Sankey';
    const TYPE_CALENDAR_CHART   = 'Calendar';
    const TYPE_TIMELINE_CHART   = 'Timeline';
    const TYPE_HISTOGRAM_CHART  = 'Histogram';

    public function toOptionArray()
    {
        return array(
            array("title"  =>Mage::helper('cleansql')->__('Simple Charts'), 'label' => Mage::helper('cleansql')->__('Simple Charts'), 'value' => array(
                array('value' =>self::TYPE_PIE_CHART, 'title' =>Mage::helper('cleansql')->__('Pie Chart'), 'label' => Mage::helper('cleansql')->__('Pie Chart')),
                array('value' =>self::TYPE_BAR_CHART, 'title' =>Mage::helper('cleansql')->__('Bar Chart'), 'label' => Mage::helper('cleansql')->__('Bar Chart')),
                array('value' =>self::TYPE_GEO_CHART, 'title' =>Mage::helper('cleansql')->__('Geo Chart'), 'label' => Mage::helper('cleansql')->__('Geo Chart')),
                array('value' =>self::TYPE_AREA_CHART, 'title' =>Mage::helper('cleansql')->__('Area Chart'), 'label' => Mage::helper('cleansql')->__('Area Chart')),
                array('value' =>self::TYPE_LINE_CHART, 'title' =>Mage::helper('cleansql')->__('Line Chart'), 'label' => Mage::helper('cleansql')->__('Line Chart')),
                array('value' =>self::TYPE_COMBO_CHART, 'title' =>Mage::helper('cleansql')->__('Combo Chart'), 'label' => Mage::helper('cleansql')->__('Combo Chart')),
                array('value' =>self::TYPE_BUBBLE_CHART, 'title' =>Mage::helper('cleansql')->__('Bubble Chart'), 'label' => Mage::helper('cleansql')->__('Bubble Chart')),
                array('value' =>self::TYPE_COLUMN_CHART, 'title' =>Mage::helper('cleansql')->__('Column Chart'), 'label' => Mage::helper('cleansql')->__('Column Chart')),
                array('value' =>self::TYPE_TREEMAP_CHART, 'title' =>Mage::helper('cleansql')->__('TreeMap Chart'), 'label' => Mage::helper('cleansql')->__('TreeMap Chart')),
                array('value' =>self::TYPE_SCATTER_CHART, 'title' =>Mage::helper('cleansql')->__('Scatter Chart'), 'label' => Mage::helper('cleansql')->__('Scatter Chart')),
                array('value' =>self::TYPE_CANDLESTICK_CHART, 'title' =>Mage::helper('cleansql')->__('Candlestick Chart'), 'label' => Mage::helper('cleansql')->__('Candlestick Chart')),
                array('value' =>self::TYPE_STEPPEDAREA_CHART, 'title' =>Mage::helper('cleansql')->__('SteppedArea Chart'), 'label' => Mage::helper('cleansql')->__('SteppedArea Chart')),
            )),

            array("title"  =>Mage::helper('cleansql')->__('Complex Charts'), 'label' => Mage::helper('cleansql')->__('Complex Charts'), 'value' => array(
                array('value' =>self::TYPE_ORG_CHART, 'title' =>Mage::helper('cleansql')->__('Org Chart'), 'label' => Mage::helper('cleansql')->__('Org Chart')),
                array('value' =>self::TYPE_SANKEY_CHART, 'title' =>Mage::helper('cleansql')->__('Sankey Chart'), 'label' => Mage::helper('cleansql')->__('Sankey Chart')),
                array('value' =>self::TYPE_TIMELINE_CHART, 'title' =>Mage::helper('cleansql')->__('Timeline Chart'), 'label' => Mage::helper('cleansql')->__('Timeline Chart')),
                array('value' =>self::TYPE_HISTOGRAM_CHART, 'title' =>Mage::helper('cleansql')->__('Histogram Chart'), 'label' => Mage::helper('cleansql')->__('Histrogram Chart')),
                array('value' =>self::TYPE_CALENDAR_CHART, 'title' =>Mage::helper('cleansql')->__('Calendar Chart'), 'label' => Mage::helper('cleansql')->__('Calendar Chart'))
            ))
        );
    }

}