<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

function smarty_modifier_sanitize($string)
{
    $string = strtolower($string); 
    $code_entities_match =   array('á','é','í','ó','ú','Á','É','Í','Ó','Ú','ñ','Ñ', ' ','--','&quot;','!','@','#','$','%','^','&','*','(',')','_','+','{','}','|',':','"','<','>','?','[',']','\\',';',"'",',','.','/','*','+','~','`','='); 
    $code_entities_replace = array('a','e','i','o','u','A','E','I','O','U','n','N', '-','-','','','','','','','','','','','','','','','','','','','','','','','',''); 
    $string = str_replace($code_entities_match, $code_entities_replace, $string); 
    return $string;
}

/* vim: set expandtab: */

?>
