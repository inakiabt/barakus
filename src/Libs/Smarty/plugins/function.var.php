<?php
/**
 * Smarty {var} plugin
 *
 * Type:       function
 * Name:       var
 * Date:       Mar 30, 2009
 * Purpose:    Assign values to template variables in a more intuitive manner than {assign}
 * Syntax:     {var welcome="Hello world!"}: assign "Hello World!" to $welcome
 *             In one {var} statement values can be assigned to multiple variables
 * Install:    Drop into the plugin directory
 * @link       http://jlix.net/extensions/smarty/var
 * @author     Sander Aarts <smarty at jlix dot net>
 * @copyright  2009 Sander Aarts
 * @license    LGPL License
 * @version    1.0
 * @param      array
 * @param      Smarty
 */
function smarty_function_var($params, &$smarty)
{
	if (count($params)===0) {
		$smarty->trigger_error("{var}: no variables declared");
		return;
	}
    $smarty->assign($params);
}


?>
