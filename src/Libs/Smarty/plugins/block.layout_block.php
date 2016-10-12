<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

function smarty_block_layout_block($params, $content, &$smarty, &$repeat){
    if ( empty($content) ) 
    {
        return;
    }
    $smarty->assign('LAYOUTVAR_' . $params['id'], $content);
    $tpl = $params['tpl'];
    
    return $smarty->fetch($smarty->__createTemplateFile($tpl));
}    
?>