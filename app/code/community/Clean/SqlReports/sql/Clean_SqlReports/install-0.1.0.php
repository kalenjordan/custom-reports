<?php

/** @var $this Mage_Core_Model_Resource_Setup */
$this->startSetup();

$table = $this->getConnection()->newTable($this->getTable('cleansql/report'));
$table->addColumn(
    'report_id',
    Varien_Db_Ddl_Table::TYPE_INTEGER,
    null,
    array(
        'identity' => true,
        'primary'  => true,
        'unsigned' => true,
        'nullable' => false,
    )
);
$table->addColumn(
    'sql_query',
    Varien_Db_Ddl_Table::TYPE_TEXT,
    65536,
    array(
        'nullable' => false,
    )
);
$table->addColumn(
    'title',
    Varien_Db_Ddl_Table::TYPE_TEXT,
    255,
    array(
        'nullable' => false,
    )
);
$table->addColumn(
    'created_at',
    Varien_Db_Ddl_Table::TYPE_DATETIME,
    null
);
$this->getConnection()->createTable($table);

$this->endSetup();
