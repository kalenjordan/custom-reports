<?php

class Clean_SqlReports_Block_Adminhtml_Widget_Grid_Column_Renderer_Action_View
    extends Clean_SqlReports_Block_Adminhtml_Widget_Grid_Column_Renderer_Action
{
    /**
     * @param Clean_SqlReports_Model_Report $row
     * @return string|void
     */
    public function render(Varien_Object $row)
    {
        $actions = $this->getColumn()->getData('actions');
        for ($i = 0; $i < count($actions); $i++) {
            $action = $actions[$i];
            if ($action['caption'] == 'Chart' && !$row->hasChart()) {
                unset($actions[$i]);
            }

            $i++;
        }
        $actions = array_values($actions);
        $this->getColumn()->setData('actions', $actions);

        return parent::render($row);
    }
}