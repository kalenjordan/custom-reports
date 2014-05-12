<?php

class Clean_SqlReports_SqlReportsController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->_redirect('*/sqlReports_report');
    }
}
