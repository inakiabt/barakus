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

function smarty_block_atras($params, $content, &$smarty)
{
    if (!empty($content))
    {
        $sender = Request::Sender();
        if (!eregi('^'.APP_URL, $sender))
        {
            $params['href'] = $params['default'];    
        } else {
            $params['href'] = $sender;
        }
        unset($params['default']);
        return ViewHelper::link($params) . $content . '</a>';
    }
}

?>