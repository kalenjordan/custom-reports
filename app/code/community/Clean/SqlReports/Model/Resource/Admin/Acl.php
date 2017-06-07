<?php

class Clean_SqlReports_Model_Resource_Admin_Acl extends Mage_Admin_Model_Resource_Acl {

    public function loadAcl()
    {
        $acl = Mage::getModel('admin/acl');

        Mage::getSingleton('admin/config')->loadAclResources($acl);
        $this->loadReportResources($acl);

        $roleTable   = $this->getTable('admin/role');
        $ruleTable   = $this->getTable('admin/rule');
        $assertTable = $this->getTable('admin/assert');

        $adapter = $this->_getReadAdapter();

        $select = $adapter->select()
            ->from($roleTable)
            ->order('tree_level');

        $rolesArr = $adapter->fetchAll($select);

        $this->loadRoles($acl, $rolesArr);

        $select = $adapter->select()
            ->from(array('r' => $ruleTable))
            ->joinLeft(
                array('a' => $assertTable),
                'a.assert_id = r.assert_id',
                array('assert_type', 'assert_data')
            );

        $rulesArr = $adapter->fetchAll($select);

        $this->loadRules($acl, $rulesArr);

        return $acl;
    }

    /**
     * Add report resources
     *
     * @param Mage_Admin_Model_Acl $acl
     */
    protected function loadReportResources(Mage_Admin_Model_Acl $acl)
    {
        $reportCollection = Mage::getModel('cleansql/report')->getCollection()->setOrder('title', 'ASC');

        foreach ($reportCollection as $report) {
            $acl->add(
                Mage::getModel('admin/acl_resource',Clean_SqlReports_Helper_Data::RESOURCE_VIEW_REPORT_PREFIX . $report->getId()),
                $acl->get('admin/report/cleansql')
            );
        }
    }

}