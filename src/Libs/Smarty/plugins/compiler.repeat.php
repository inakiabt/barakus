<?php
/*
* BUG: por ejemplo con tablas, si hay que llenar columnas no se puede cerrar la fila
*/


$this->register_compiler_function('/repeat', 'smarty_compiler_endrepeat');
$this->register_compiler_function('elserepeat', 'smarty_compiler_elserepeat');
$this->register_compiler_function('repeat_inic', 'smarty_compiler_repeat_inic');
$this->register_compiler_function('/repeat_inic', 'smarty_compiler_endrepeat_inic');
$this->register_compiler_function('repeat_end', 'smarty_compiler_repeat_end');
$this->register_compiler_function('/repeat_end', 'smarty_compiler_endrepeat_end');
$this->register_compiler_function('repeat_fill', 'smarty_compiler_repeat_fill');

function smarty_compiler_repeat($tag_arg, &$smarty)
{
   	$_attrs = $smarty->_parse_attrs($tag_arg);
	
	srand((double)microtime()*1000000);
	if (isset($_attrs['key'])) 
	{
		$_attrs['key'] = $smarty->_dequote($_attrs['key']);
		if (!preg_match('~^\w+$~', $_attrs['key']))
		{
			return $smarty->_syntax_error("repeat: 'key' must to be a variable name (literal string)", E_USER_ERROR, __FILE__, __LINE__);
		}
	} else {		
		$_attrs['key'] = '__key_' . rand(0, 100);
	}
	$key_part = "\$this->_tpl_vars['".$_attrs['key']."']";
	
	if (empty($_attrs['loop']))
	{
		return $smarty->_syntax_error("repeat: missing 'loop' attribute", E_USER_ERROR, __FILE__, __LINE__);
	} elseif (!ctype_digit($_attrs['loop'])) {
		return $smarty->_syntax_error("repeat: 'loop' must be numerical", E_USER_ERROR, __FILE__, __LINE__);
	}
	
	if (empty($_attrs['from']))
	{
		return $smarty->_syntax_error("repeat: missing 'from' attribute", E_USER_ERROR, __FILE__, __LINE__);
	}
	
	$_attrs['item'] = $smarty->_dequote($_attrs['item']);
	if (!preg_match('~^\w+$~', $_attrs['item'])) 
	{
		return $smarty->_syntax_error("'repeat: 'item' must be a variable name (literal string)", E_USER_ERROR, __FILE__, __LINE__);
	}

	if (!is_array($GLOBALS['__repeater']))
	{
		$GLOBALS['__repeater']          = array();
		$GLOBALS['__repeater_last_tag'] = array();
	}
	$_attrs['total'] = '__total_'.rand(0, 100);
	$_attrs['index'] = '__index_'.rand(0, 100);
    $_attrs['var']   = '$__var'.$_attrs['index'];
	array_push($GLOBALS['__repeater'], $_attrs);
	array_push($GLOBALS['__repeater_last_tag'], 'repeat');

   	return $_attrs['var']." = ".$_attrs['from']."; if (\$".$_attrs['total']." = count(".$_attrs['var'].")):
	\$".$_attrs['index']." = 0;
   	foreach (".$_attrs['var']." as $key_part => \$this->_tpl_vars['".$_attrs['item']."']):";
}

function smarty_compiler_endrepeat($tag_arg, &$smarty)
{
	$_attrs   = array_pop($GLOBALS['__repeater']);
	$last_tag = array_pop($GLOBALS['__repeater_last_tag']);
	
	$tag_repeat_fill = ($last_tag == 'repeat_fill');
	
	$return = "";
	if ($last_tag == 'repeat' || $tag_repeat_fill)
	{
		if ($tag_repeat_fill)
		{
			$return = "endfor;\n";
		} else {
			$return = __end_repeat($_attrs);
		}
	}
	return $return . " endif;";
}

function __end_repeat($_attrs)
{
	return "\$".$_attrs['index']."++;
		endforeach;\n";		
}

function smarty_compiler_repeat_fill($tag_arg, &$smarty)
{
	array_push($GLOBALS['__repeater_last_tag'], 'repeat_fill');
	$_attrs = $GLOBALS['__repeater'][count($GLOBALS['__repeater'])-1];
	
	return __end_repeat($_attrs) ."for (\$__k = 0; \$__k < (".$_attrs['loop']." - (\$".$_attrs['index']." % ".$_attrs['loop'].")); \$__k++):\n";
}

function smarty_compiler_repeat_inic($tag_arg, &$smarty)
{
	$_attrs = $GLOBALS['__repeater'][count($GLOBALS['__repeater'])-1];
	return "if (\$".$_attrs['index']." % ".$_attrs['loop']." == 0):\n";
}

function smarty_compiler_endrepeat_inic($tag_arg, &$smarty)
{
	return "endif;\n";
}

function smarty_compiler_repeat_end($tag_arg, &$smarty)
{
	$_attrs = $GLOBALS['__repeater'][count($GLOBALS['__repeater'])-1];
	return "if (\$".$_attrs['index']." % ".$_attrs['loop']." == (".$_attrs['loop']." - 1)):\n";
}

function smarty_compiler_endrepeat_end($tag_arg, &$smarty)
{
	return "endif;";
}

function smarty_compiler_elserepeat($tag_arg, &$smarty)
{
	$_attrs   = $GLOBALS['__repeater'][count($GLOBALS['__repeater']) - 1];
	$last_tag = array_pop($GLOBALS['__repeater_last_tag']);
	
	if ($last_tag == 'repeat_fill')
	{
		$return = "endfor;\n";
	} else {
		$return = __end_repeat($_attrs);
	}
	array_push($GLOBALS['__repeater_last_tag'], 'elserepeat');

	return $return . " else:\n";
}

?>