<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty upper modifier plugin
 *
 * Type:     modifier<br>
 * Name:     upper<br>
 * Purpose:  convert string to uppercase
 * @link http://smarty.php.net/manual/en/language.modifier.upper.php
 *          upper (Smarty online manual)
 * @author   Monte Ohrt <monte at ohrt dot com>
 * @param string
 * @return string
 */
function smarty_modifier_upper($string)
{
    $tildes_low = array('á', 'é', 'í', 'ó', 'ú');
    $tildes_up  = array('Á', 'É', 'Í', 'Ó', 'Ú');
    return str_replace($tildes_low, $tildes_up, strtoupper($string));
}

?>
