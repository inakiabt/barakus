<?php
    class ViewPlugin
    {
        var $params;
        var $view;
        
        function ViewPlugin($params, &$smarty)
        {
            $this->params = $params;
            $this->view   = &$smarty;
        }
        
        function plug()
        {
            die('implements this method is needed (plug in ViewPlugin)');
        }
    }
?>
