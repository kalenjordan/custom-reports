<?php
/**
 * Created by PhpStorm.
 * User: amacgregor
 * Date: 11/05/14
 * Time: 8:33 AM
 */

class Clean_SqlReports_Model_Config_OutputType extends Mage_Core_Model_Config_Data
{
    const TYPE_PIE_CHART = 'PieChart';
    const TYPE_BAR_CHART = 'BarChart';
    const TYPE_TABLE_CHART = 'Table';
    const TYPE_COLUMN_CHART = 'ColumnChart';
    const TYPE_CALENDAR_CHART = 'Calendar';


    public function toOptionArray()
    {
        return array(
            array('value' =>self::TYPE_TABLE_CHART, 'title' =>Mage::helper('cleansql')->__('Table'), 'label' => Mage::helper('cleansql')->__('Table')),
            array('value' =>self::TYPE_PIE_CHART, 'title' =>Mage::helper('cleansql')->__('Pie Chart'), 'label' => Mage::helper('cleansql')->__('Pie Chart')),
            array('value' =>self::TYPE_BAR_CHART, 'title' =>Mage::helper('cleansql')->__('Bar Chart'), 'label' => Mage::helper('cleansql')->__('Bar Chart')),
            array('value' =>self::TYPE_COLUMN_CHART, 'title' =>Mage::helper('cleansql')->__('Column Chart'), 'label' => Mage::helper('cleansql')->__('Column Chart')),
            array('value' =>self::TYPE_CALENDAR_CHART, 'title' =>Mage::helper('cleansql')->__('Calendar Chart'), 'label' => Mage::helper('cleansql')->__('Calendar Chart'))
        );
    }

}