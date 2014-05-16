<?php
/**
 * Connection Config source model 
 * @author Rolando Granadino <beeplogic@magenation.com>
 */
class Clean_SqlReports_Model_Config_Connections
{
    /**
     * generate option array based on configured database resources
     * @return array
     */
    public function toOptionArray()
    {
        /* @var $resourceConfig Mage_Core_Model_Config_Element */
        $resourceConfig = Mage::getConfig()->getXpath('global/resources/*[child::connection and descendant::active=1]');
        $options        = array();

        foreach ($resourceConfig as $connNode) {
            /* @var $connNode Mage_Core_Model_Config_Element */
            $options[] = array(
               'label' => $connNode->getName(),
               'value' => $connNode->getName()
            );
        }

        return $options;
    }
}
