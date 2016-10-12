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

function smarty_block_textarea($params, $content, &$smarty)
{
    global $_textarea;
    if ($_textarea)
    {
        $state    = $smarty->get_template_vars('state');                   
        $notValid = intval($smarty->get_template_vars('ValidationFailed')) || intval($smarty->get_template_vars('FormEdit'));
                                
        echo ViewHelper::textarea($params);
        if ($notValid)
        {
            echo htmlentities(ViewHelper::_getStateValue($state, $params['name']));
        } else {
            echo $content;
        }
        echo '</textarea>';
        $_textarea = false;
    } else {
        $_textarea = true;
    }
}

?>