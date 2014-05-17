<?php
/** @var $this Mage_Core_Model_Resource_Setup */
$this->startSetup();

$table = $this->getConnection()->newTable($this->getTable('cleansql/chart'));
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
    'title',
    Varien_Db_Ddl_Table::TYPE_TEXT,
    255,
    array(
        'nullable' => false,
    )
);
$table->addColumn(
    'report_id',
    Varien_Db_Ddl_Table::TYPE_INTEGER,
    null,
    array(
        'unsigned' => true,
        'nullable' => true,
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
    'output_type',
    Varien_Db_Ddl_Table::TYPE_TEXT,
    255,
    array(
        'nullable' => false,
    )
);
$table->addColumn(
    'chart_config',
    Varien_Db_Ddl_Table::TYPE_TEXT,
    65536,
    array()
);
$table->addColumn(
    'created_at',
    Varien_Db_Ddl_Table::TYPE_DATETIME,
    null
);

$table->addForeignKey(
    $this->getFkName('cleansql/chart', 'report_id', 'cleansql/report', 'report_id'),
    'report_id',
    $this->getTable('cleansql/report'),
    'report_id',
    Varien_Db_Ddl_Table::ACTION_CASCADE,
    Varien_Db_Ddl_Table::ACTION_CASCADE
);
$this->getConnection()->createTable($table);

$this->getConnection()->dropColumn($this->getTable('cleansql/report'), 'output_type');
$this->getConnection()->dropColumn($this->getTable('cleansql/report'), 'chart_config');

$this->endSetup();
