<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

function smarty_compiler_content($params, &$smarty)
{
    $params = $smarty->_parse_attrs($params);
    $params['id'] = $smarty->_dequote($params['id']); 
    if (!isset($params['id']))                                         
    {
        $smarty->_trigger_fatal_error("[content] parameter 'id' cannot be empty");
    }
    
    return "echo \$this->_tpl_vars['LAYOUTVAR_{$params['id']}']; unset(\$this->_tpl_vars['LAYOUTVAR_{$params['id']}']);";
}

/* vim: set expandtab: */

?>
