<?php

/** @var $this Mage_Core_Model_Resource_Setup */
$this->startSetup();

$this->getConnection()
    ->addColumn($this->getTable('cleansql/report'), 'output_type', array(
        'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
        'length'    => '255',
        'comment'   => 'Output Type',
    ));

$this->getConnection()
    ->addColumn($this->getTable('cleansql/report'), 'chart_config', array(
        'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
        'length'    => '65536',
        'comment'   => 'Chart Configuration',
    ));

$this->endSetup();