<?php

/**
 * @author Lee Saferite <lee.saferite@aoe.com>
 * @since  2/28/14
 */
class Clean_SqlReports_Block_Adminhtml_Widget_Grid_Column_Renderer_Action extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Action
{
    /**
     * Renders column
     *
     * @param Varien_Object $row
     *
     * @return string
     */
    public function render(Varien_Object $row)
    {
        $actions = $this->getColumn()->getActions();
        if (empty($actions) || !is_array($actions)) {
            return '&nbsp;';
        }

        $out = '';

        $linkLimit = max(intval($this->getColumn()->getLinkLimit()), 1);
        if (count($actions) <= $linkLimit && !$this->getColumn()->getNoLink()) {
            foreach ($actions as $action) {
                if (is_array($action)) {
                    $out .= ' ' . $this->_toLinkHtml($action, $row);
                }
            }
        } else {
            $out .= '<select class="action-select" onchange="varienGridAction.execute(this);">';
            $out .= '<option value=""></option>';
            foreach ($actions as $action) {
                if (is_array($action)) {
                    $out .= $this->_toOptionHtml($action, $row);
                }
            }
            $out .= '</select>';
        }

        return $out;
    }
}
