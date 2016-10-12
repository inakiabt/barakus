<?php
    function smarty_compiler_addjs($params, &$smarty)
    {
        $params = $smarty->_parse_attrs($params);
        $params['file'] = $smarty->_dequote($params['file']); 
        
        if (empty($params['file'])) 
        {
            return $smarty->_syntax_error("'addjs: 'file' can not be empty", E_USER_ERROR, __FILE__, __LINE__);
        }        
        if ($smarty->_tpl_vars['this'])
        {
            $smarty->_tpl_vars['this']->addJs($params['file']);
        }
        return;
    }
?>
