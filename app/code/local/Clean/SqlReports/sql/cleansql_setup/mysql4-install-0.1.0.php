<?php

/** @var $this KJ_AutoEmail_Model_Mysql4_Setup */
$this->startSetup();

$this->createTable($this->getTable('cleansql/report'),
    array('report_id' => 'INT(11) UNSIGNED NOT NULL auto_increment'),
    array(
        'sql_query'     => 'TEXT',
        'title'         => 'VARCHAR(255)',
        'created_at'    => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
    )
);

$this->endSetup();