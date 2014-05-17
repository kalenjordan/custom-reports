<?php

/** @var $this Mage_Core_Model_Resource_Setup */
$this->startSetup();

$table = $this->getConnection()->newTable($this->getTable('cleansql/result'));
$table->addColumn(
    'id',
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
    'report_id',
    Varien_Db_Ddl_Table::TYPE_INTEGER,
    null,
    array(
        'unsigned' => true,
        'nullable' => false,
    )
);
$table->addColumn(
    'created_at',
    Varien_Db_Ddl_Table::TYPE_DATETIME,
    null,
    array(
        'nullable' => false,
    )
);
$table->addColumn(
    'table_suffix',
    Varien_Db_Ddl_Table::TYPE_TEXT,
    64,
    array(
        'nullable' => false,
    )
);
$table->addForeignKey(
   $this->getFkName('cleansql/result', 'report_id', 'cleansql/report', 'report_id'),
    'report_id',
   $this->getTable('cleansql/report'),
    'report_id',
    Varien_Db_Ddl_Table::ACTION_CASCADE,
    Varien_Db_Ddl_Table::ACTION_CASCADE
);
$this->getConnection()->createTable($table);

$this->endSetup();
