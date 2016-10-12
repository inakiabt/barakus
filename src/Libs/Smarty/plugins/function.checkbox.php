<?PHP /* $Id: function.textarea.php,v 1.1.1.1 2002/09/13 17:52:11 darkelder Exp $ */

/*
 * Form Plugin - function form
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

function smarty_function_checkbox($params, &$smarty)
{
    $state    = $smarty->get_template_vars('state');
    $notValid = intval($smarty->get_template_vars('ValidationFailed')) || intval($smarty->get_template_vars('FormEdit'));

    $data     = ViewHelper::_getStateValue($state, $params['name'], false);
    $checked = $params['checked'];
    unset($params['checked']);
    $disabled = $params['disabled'];
    unset($params['disabled']);
    if ($disabled)
    {
        $params['disabled'] = 'disabled';
    }
    if (!intval($smarty->get_template_vars('ValidationFailed')) && intval($smarty->get_template_vars('FormEdit')) && $data == $params['value'])
    {
        $params['checked'] = 'checked';
    }
    if (($notValid && is_array($data) && in_array($params['value'], $data)) || (!$notValid && $checked == '1'))
    {
        $params['checked'] = 'checked';   
    }                     
    $global_name  = '__checkbox.'.$params['name'];
    $global_value = intval($GLOBALS[$global_name]);        
    $params['name'] .= '.'.$global_value;
    $GLOBALS[$global_name] = $global_value + 1;
    ViewHelper::checkbox($params);
}

?>