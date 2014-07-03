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
        $resourceConfig = Mage::helper('cleansql')->getConnectionResourceConfig();
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
