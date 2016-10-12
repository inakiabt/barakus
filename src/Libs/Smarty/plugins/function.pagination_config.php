<?php

/**
 * Project:     SmartyPaginate: Pagination for the Smarty Template Engine
 * File:        function.paginate_next.php
 * Author:      Monte Ohrt <monte at newdigitalgroup dot com>
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * @link http://www.phpinsider.com/php/code/SmartyPaginate/
 * @copyright 2001-2005 New Digital Group, Inc.
 * @author Monte Ohrt <monte at newdigitalgroup dot com>
 * @package SmartyPaginate
 * @version 1.6-dev
 */

function smarty_function_pagination_config($params, &$smarty) {

    $_id = empty($params['id']) ? 'p' : $params['id'];
    $tpl  = '<a href="{%url}">{%page}</a>';
    $selected  = '{%page}';
    $exception = true;
    foreach($params as $_key => $_val) {
        switch($_key) {
            case 'exception':
                $exception = (bool) $_val;
                break;
            case 'tpl':
                $tpl = preg_replace('|@(\w*)@|', "{%\$1}", $_val);
                break;
            case 'selected':
                $selected = preg_replace('|@(\w*)@|', "{%\$1}", $_val);
                break;
        }
    }    
    if ($exception && !is_object(Model::getModelInstance('AppData')->getPaginationLayout($_id))) {
        $smarty->trigger_error("pagination_config ($_id): paginate not initialized");
        return;
    }    
    $GLOBALS['pagination_config'][$_id]['exception'] = $exception;
    $GLOBALS['pagination_config'][$_id]['tpl']       = $tpl;
    $GLOBALS['pagination_config'][$_id]['selected']  = $selected;
    
    return '';
}

?>
