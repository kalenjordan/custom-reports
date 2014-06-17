<?php
/** @var $this Mage_Core_Model_Resource_Setup */
$this->startSetup();

$this->getConnection()->addColumn(
    $this->getTable('cleansql/result'),
    'start_date',
    Varien_Db_Ddl_Table::TYPE_DATE,
    null
);

$this->getConnection()->addColumn(
    $this->getTable('cleansql/result'),
    'end_date',
    Varien_Db_Ddl_Table::TYPE_DATE,
    null
);

$this->endSetup();
