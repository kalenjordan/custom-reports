<?php

/**
 * @method Mage_Adminhtml_Block_Widget_Grid_Column getColumn()
 */
class Clean_SqlReports_Block_Adminhtml_Widget_Grid_Column_Renderer_Action extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Action
{
    public function render(Varien_Object $row)
    {
        $actions = $this->getColumn()->getData('actions');
        if (empty($actions) || !is_array($actions)) {
            return '&nbsp;';
        }

        $out = '';

        $linkLimit = max(intval($this->getColumn()->getData('link_limit')), 1);
        if (count($actions) <= $linkLimit && !$this->getColumn()->getData('no_link')) {
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
