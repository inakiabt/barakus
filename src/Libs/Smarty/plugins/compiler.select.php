<?php
/*
* BUG: por ejemplo con tablas, si hay que llenar columnas no se puede cerrar la fila
*/


$this->register_compiler_function('/select', 'smarty_compiler_endselect');

function smarty_compiler_select($tag_arg, &$smarty)
{
    $_attrs = $params = $smarty->_parse_attrs($tag_arg);
    unset($params['from'], $params['item'], $params['start'], $params['end']);
    foreach ($params as $key => $value)
    {
        $params[$key] = $smarty->_dequote($value);
    }
    
    if (empty($_attrs['key']))
    {
        $_attrs['key'] = '__key_'.rand(0, 100);
    }
	
    $_attrs['item'] = $smarty->_dequote($_attrs['item']);
	if (!empty($_attrs['from']) && !preg_match('~^\w+$~', $_attrs['item'])) 
	{
		return $smarty->_syntax_error("'select: 'item' must be a variable name (literal string)", E_USER_ERROR, __FILE__, __LINE__);
	}

	if (!is_array($GLOBALS['__select']))
	{
		$GLOBALS['__select']          = array();
        $GLOBALS['__select_last_tag'] = array();
        $GLOBALS['__select_from']     = array();
	}
	$_attrs['total'] = '__total_'.rand(0, 100);
	$_attrs['index'] = '__index_'.rand(0, 100);
	array_push($GLOBALS['__select'], $_attrs);
    if (empty($_attrs['from']))
    {
        if (empty($_attrs['start']) && $_attrs['start'] != '0')
        {
            return $smarty->_syntax_error("'select: 'start' is required (literal string)", E_USER_ERROR, __FILE__, __LINE__);
        }
        if (empty($_attrs['end']) && $_attrs['end'] != '0')
        {
            return $smarty->_syntax_error("'select: 'end' is required (literal string)", E_USER_ERROR, __FILE__, __LINE__);
        }
        $_attrs['start'] = intval($_attrs['start']);
        $_attrs['end']   = intval($_attrs['end']);

        $ret  = "array_push(\$GLOBALS['__select_from'], range(".$_attrs['start'].", ".$_attrs['end']."));";
        $ret .= "\$".$_attrs['total']." = (count(\$GLOBALS[\"__select_from\"])-1);";
        $_attrs['from'] = '$GLOBALS["__select_from"][$'.$_attrs['total'].']';
    }
    array_push($GLOBALS['__select_last_tag'], 'select');
    
    $key = '';
    if (!empty($params['key']))
    {
        $key = "\$this->_tpl_vars['".$params['key']."'] =>";
    }

   	$ret .= "
    \$state    = \$this->get_template_vars('state');
    \$this->assign('SelectOptionValue', ViewHelper::_getStateValue(\$state, '{$params['name']}'));
    echo \"".str_replace(array("´\\\"", "\\\"´"), '"', str_replace('"', '\"', ViewHelper::select($params)))."\";";
    if (!empty($params['default']))
    {
        $ret .= 'echo "<option value=\"'.$params['default'].'\">' . $params['default'] . '</option>";';
    }
    
    $ret .="
    if (count(".$_attrs['from'].")):
	\$".$_attrs['index']." = 0;
   	foreach (".$_attrs['from']." as $key \$this->_tpl_vars['".$_attrs['item']."']):
    ";
    return $ret;
}

function smarty_compiler_endselect($tag_arg, &$smarty)
{
	$_attrs   = array_pop($GLOBALS['__select']);
    $last_tag = array_pop($GLOBALS['__select_last_tag']);
    $pop = "if (is_array(\$GLOBALS['__select_from']))
    {
        array_pop(\$GLOBALS['__select_from']);
    }";
	
	$return = "";
	if ($last_tag == 'select')
	{
		$return = "\$".$_attrs['index']."++;
        endforeach;\n";
	}
	return $return . " \$this->assign('SelectOptionValue', ''); endif; echo '</select>';".$pop;
}
?>