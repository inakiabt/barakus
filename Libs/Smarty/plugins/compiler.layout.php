<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty {layout} compiler function plugin
 *
 * Type:     compiler function<br>
 * Name:     layout<br>
 * Purpose:  assign a layout to a template
 * @author Iñaki Abete <inakiabt at gmail dot com> (initial author)
 * @param string containing layout-attribute
 * @param Smarty_Compiler
 */
function smarty_compiler_layout($_params, &$smarty) 
{
    $_params = $smarty->_parse_attrs($_params);
    $_params['id'] = $smarty->_dequote($_params['id']); 
    $_params['tpl'] = $smarty->_dequote($_params['tpl']); 

    if (!isset($_params['tpl'])) {
        $smarty->_trigger_fatal_error("[layout] parameter 'tpl' cannot be empty");
        return;
    }

    if (!isset($_params['id'])) {
        $smarty->_trigger_fatal_error("[layout] parameter 'id' cannot be empty");
        return;
    }
}

/* vim: set expandtab: */

?>
