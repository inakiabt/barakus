<?php

/**
 * Project:     SmartyPaginate: Pagination for the Smarty Template Engine
 * File:        function.paginate_last.php
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

function smarty_function_paginate_last($params, &$smarty) {

    $_id = empty($params['id']) ? 'p' : $params['id'];
    foreach($params as $_key => $_val) {
        switch($_key) {
            case 'id':
                break;
            default:
                $_attrs[] = $_key . '="' . $_val . '"';
                break;   
        }
    }    
    $_attrs = !empty($_attrs) ? ' ' . implode(' ', $_attrs) : '';
        
    if (!is_object($smarty->paginate->$_id)) {
        $smarty->trigger_error("paginate_last: paginate not initialized");
        return;
    }
    
    if ($smarty->paginate->$_id->getTotal() === false) {
        $smarty->trigger_error("paginate_last: total was not set");
        return;        
    }
    
    $url  = $smarty->url($smarty->paginate->$_id->getURL());
    $_url = preg_replace('|('.$smarty->paginate->$_id->getUrlVar().'=[0-9]*&pagids=.*)|', '', $url);
    
    $_total = $smarty->paginate->$_id->getTotal();
    $_limit = $smarty->paginate->$_id->getLimit();
    
    $_text = isset($params['text']) ? $params['text'] : 'last';
    $_url .= (strpos($_url, '?') === false) ? '?' : '&';
    $_url .= $smarty->paginate->$_id->getUrlVar() . '=' . ($_total % $_limit > 0) ? $_total - ( $_total % $_limit ) + 1 : $_total - $_limit + 1;
    $_url .= '&pagids=' . $_id . (empty($params['anchor']) ? '' : '#' . $params['anchor']);
    
    return '<a href="' . $smarty->url($_url) . '" title="'.(($_total % $_limit > 0) ? $_total - ( $_total % $_limit ) + 1 : $_total - $_limit + 1).'"' . $_attrs . '>' . $_text . '</a>';
        
}

?>
