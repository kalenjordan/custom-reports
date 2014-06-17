<?php

require_once 'abstract.php';

class Clean_SqlReports_Shell_SqlReports extends Mage_Shell_Abstract
{
    /**
     * Run script
     *
     * @return void
     */
    public function run()
    {
        $action = $this->getArg('action');
        if (empty($action)) {
            echo $this->usageHelp();
        } else {
            $actionMethodName = $action.'Action';
            if (method_exists($this, $actionMethodName)) {
                $this->$actionMethodName();
            } else {
                echo "Action $action not found!\n";
                echo $this->usageHelp();
                exit(1);
            }
        }
    }

    /**
     * Retrieve Usage Help Message
     *
     * @return string
     */
    public function usageHelp()
    {
        $help = 'Available actions: ' . "\n";
        $methods = get_class_methods($this);
        foreach ($methods as $method) {
            if (substr($method, -6) == 'Action') {
                $help .= '    -action ' . substr($method, 0, -6);
                $helpMethod = $method.'Help';
                if (method_exists($this, $helpMethod)) {
                    $help .= $this->$helpMethod();
                }
                $help .= "\n";
            }
        }
        return $help;
    }

    /**
     * Schedule a job now
     *
     * @return void
     */
    public function runReportAction()
    {
        $id = $this->getArg('id');
        if (empty($id)) {
            echo "\nNo id found!\n\n";
            echo $this->usageHelp();
            exit(1);
        }
        $report = Mage::getModel('cleansql/report')->load($id); /* @var $report Clean_SqlReports_Model_Report */
        if (!$report->getId()) {
            echo "\nReport with id $id not found!\n";
            exit(1);
        }

        if ($report->hasReportingPeriod() && (!$this->getArg('start_date') || !$this->getArg('end_date'))) {
            echo "\nPlease specify reporting period.\n";
            exit(1);
        }

        $report->run(array(
            'start_date' => $this->getArg('start_date'),
            'end_date' => $this->getArg('end_date'),
        ));
    }

    /**
     * Display extra help
     *
     * @return string
     */
    public function runReportActionHelp()
    {
        return " <id> [-start_date <ISO 8601 formatted date> -end_date <ISO 8601 formatted date>]";
    }
}

$shell = new Clean_SqlReports_Shell_SqlReports();
$shell->run();