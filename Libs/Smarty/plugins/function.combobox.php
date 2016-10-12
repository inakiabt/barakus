<?PHP /* $Id: function.select.php,v 1.1.1.1 2002/09/13 17:52:11 darkelder Exp $ */

/*
 * Form Plugin - function select
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

function smarty_function_combobox($params,&$smarty)
{
    $collection = $params['collection'];
    $value      = $params['value'];
    $valueid    = empty($params['valueid']) ? 'id' : $params['valueid'];
    $selected   = $params['selected'];
    unset($params['collection'], $params['tpl'], $params['selected'], $params['value']);
    
    if (empty($collection))
    {                           
        $smarty->trigger_error("combobox: collection not defined");
        return;
    }
    
    if (empty($selected))
    {
        $selected = ViewHelper::_getStateValue($smarty->get_template_vars('state'), $params['name']);
    }
    
    echo ViewHelper::select($params);    
    echo "\n";
    
    $value_tpl = preg_replace("|@(\w*)@|", "\".\$item->\$1.\"", str_replace('$', "\\$", $value));
    foreach ($collection as $item)
    {                                     
        eval('$values = "'.$value_tpl.'";');
        $_selected = ($selected == $item->$valueid) ? ' selected="selected"' : '';
        echo "  <option value=\"{$item->$valueid}\"{$_selected}>{$values}</option>\n";
    }
    echo "</select>";
}

?>