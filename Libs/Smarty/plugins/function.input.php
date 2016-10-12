<?PHP /* $Id: function.input.php,v 1.1.1.1 2002/09/13 17:52:11 darkelder Exp $ */

/*
 * Form Plugin - function input
 * -------------------------------------------------------------
 * Author:          Roberto Bertó <darkelder@users.sourceforge.net>
 * License:         LGPL
 * Documentation:   http://ourcms.sourceforge.net/form/
 * Latest Version:  http://ourcms.sourceforge.net/form/
 * Purpose:         Run $smarty->form to transform to html
 * Current Version: 1.0
 * Modified:        Apr, 07 2002
 * -------------------------------------------------------------
 */

function smarty_function_input($params,&$smarty)
{
    if ($params['autocomplete'] === true)
    {
        smarty_function_autocomplete($parmas, $smarty);
        unset($params['autocomplete']);
    }
    $state    = $smarty->get_template_vars('state'); 
    $notValid = intval($smarty->get_template_vars('ValidationFailed')) || intval($smarty->get_template_vars('FormEdit'));
    if ($notValid && $params['nostate'] != true)
    {
        $params['value'] = htmlentities(ViewHelper::_getStateValue($state, $params['name']));   
    }           
    unset($params['nostate']);
    ViewHelper::input($params);
}

?>