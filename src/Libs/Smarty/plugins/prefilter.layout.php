<?php

/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */
function smarty_prefilter_layout(&$tpl_output, &$compiler)
{
    $ld = $compiler->left_delimiter;
    $rd = $compiler->right_delimiter;    
    
    $search = "{$ld}\s*layout\s+(.*?){$rd}";
    // Pull out the clip blocks
    if (preg_match("!$search!is", $tpl_output, $params))
    {                                                                  
        return "{layout_block {$params[1]}}{$tpl_output}{/layout_block}";
    }                           
    return $tpl_output; 
}                            
?>