<?PHP /* $Id: function.input.php,v 1.1.1.1 2002/09/13 17:52:11 darkelder Exp $ */

/*
 * Form Plugin - function input
 * -------------------------------------------------------------
 * Author:          Roberto Bert� <darkelder@users.sourceforge.net>
 * License:         LGPL
 * Documentation:   http://ourcms.sourceforge.net/form/
 * Latest Version:  http://ourcms.sourceforge.net/form/
 * Purpose:         Run $smarty->form to transform to html
 * Current Version: 1.0
 * Modified:        Apr, 07 2002
 * -------------------------------------------------------------
 */

function smarty_function_password($params,&$smarty)
{
    ViewHelper::password($params);
}

?>