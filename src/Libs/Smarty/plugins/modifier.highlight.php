<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


function smarty_modifier_highlight($text, $string)
{
    return preg_replace("#($string)#i", "<strong>$string</strong>", $text);
}

?>
