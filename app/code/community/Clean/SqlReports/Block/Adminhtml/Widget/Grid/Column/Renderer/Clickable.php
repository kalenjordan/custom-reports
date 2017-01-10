<?php

class Clean_SqlReports_Block_Adminhtml_Widget_Grid_Column_Renderer_Clickable extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Action
{
    protected $_clickableConfig;

    public function render(Varien_Object $row)
    {
        $index = $this->getColumn()->getIndex();
        $config = $this->_getClickableConfig();

        $url = key($config[$index]);
        $urlParts = explode('/', $url);
        if(sizeof($urlParts) == 4) {
            $param = array_pop($urlParts);
            $field = current($config[$index]);
            if ($row->getData($field)) {
                $url = implode('/', $urlParts);
                return '<a href="' . $this->getUrl($url, array($param => $row->getData($field))) . '">' . $row->getData($index) . '</a>';
            }
        }
        return $row->getData($index);
    }
    
    private function _getClickableConfig()
    {
        if (is_null($this->_clickableConfig)) {
            $this->_clickableConfig = $this->_getReport()->getGridConfig()->getClickable();
        }
        return $this->_clickableConfig;
    }

    /**
     * @return Clean_SqlReports_Model_Report
     */
    protected function _getReport()
    {
        return Mage::registry('current_report');
    }
}
