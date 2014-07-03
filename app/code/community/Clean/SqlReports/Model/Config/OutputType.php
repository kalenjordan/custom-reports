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

    // Plain Magento table
    const TYPE_PLAIN_TABLE      = 'PlainTable';

    public function toOptionArray()
    {
        $helper = Mage::helper('cleansql');
        
        return array(
            array("title"  => $helper->__('Plain Table'), 'label' => $helper->__('Plain Table'), 'value' => self::TYPE_PLAIN_TABLE),
            array("title"  => $helper->__('Simple Charts'), 'label' => $helper->__('Simple Charts'), 'value' => array(
                array('value' =>self::TYPE_PIE_CHART, 'title' => $helper->__('Pie Chart'), 'label' => $helper->__('Pie Chart')),
                array('value' =>self::TYPE_BAR_CHART, 'title' => $helper->__('Bar Chart'), 'label' => $helper->__('Bar Chart')),
                array('value' =>self::TYPE_GEO_CHART, 'title' => $helper->__('Geo Chart'), 'label' => $helper->__('Geo Chart')),
                array('value' =>self::TYPE_AREA_CHART, 'title' => $helper->__('Area Chart'), 'label' => $helper->__('Area Chart')),
                array('value' =>self::TYPE_LINE_CHART, 'title' => $helper->__('Line Chart'), 'label' => $helper->__('Line Chart')),
                array('value' =>self::TYPE_COMBO_CHART, 'title' => $helper->__('Combo Chart'), 'label' => $helper->__('Combo Chart')),
                array('value' =>self::TYPE_BUBBLE_CHART, 'title' => $helper->__('Bubble Chart'), 'label' => $helper->__('Bubble Chart')),
                array('value' =>self::TYPE_COLUMN_CHART, 'title' => $helper->__('Column Chart'), 'label' => $helper->__('Column Chart')),
                array('value' =>self::TYPE_TREEMAP_CHART, 'title' => $helper->__('TreeMap Chart'), 'label' => $helper->__('TreeMap Chart')),
                array('value' =>self::TYPE_SCATTER_CHART, 'title' => $helper->__('Scatter Chart'), 'label' => $helper->__('Scatter Chart')),
                array('value' =>self::TYPE_CANDLESTICK_CHART, 'title' => $helper->__('Candlestick Chart'), 'label' => $helper->__('Candlestick Chart')),
                array('value' =>self::TYPE_STEPPEDAREA_CHART, 'title' => $helper->__('SteppedArea Chart'), 'label' => $helper->__('SteppedArea Chart')),
            )),

            array("title"  => $helper->__('Complex Charts'), 'label' => $helper->__('Complex Charts'), 'value' => array(
                array('value' =>self::TYPE_ORG_CHART, 'title' => $helper->__('Org Chart'), 'label' => $helper->__('Org Chart')),
                array('value' =>self::TYPE_SANKEY_CHART, 'title' => $helper->__('Sankey Chart'), 'label' => $helper->__('Sankey Chart')),
                array('value' =>self::TYPE_TIMELINE_CHART, 'title' => $helper->__('Timeline Chart'), 'label' => $helper->__('Timeline Chart')),
                array('value' =>self::TYPE_HISTOGRAM_CHART, 'title' => $helper->__('Histogram Chart'), 'label' => $helper->__('Histrogram Chart')),
                array('value' =>self::TYPE_CALENDAR_CHART, 'title' => $helper->__('Calendar Chart'), 'label' => $helper->__('Calendar Chart'))
            ))
        );
    }

}