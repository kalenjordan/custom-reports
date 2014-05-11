<?php
/** @var $this Mage_Core_Model_Resource_Setup */
$this->startSetup();

foreach (array('cleansql/report', 'cleansql/result') as $table) {
    $this->getConnection()->addColumn(
        $this->getTable($table),
        'column_config',
        array(
            'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
            'length'   => 65536,
            'nullable' => true,
            'comment'  => 'Column Config',
        )
    );
}

$this->endSetup();
