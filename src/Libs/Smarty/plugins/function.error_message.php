<?PHP 

/*
 * Form Plugin - function error_message
 * -------------------------------------------------------------
 */

function smarty_function_error_message($params,&$smarty)
{
    if (!isset($params['field'])) 
    {
        $smarty->_trigger_fatal_error("[plugin] parameter 'field' cannot be empty");    
    }
    if (!array_key_exists('template', $params))
    {
        $params['template'] = true;
    }
    $field = (string)$params['field'];
    if ($field[strlen($field)-1] == '.')
    {
        $pos = intval($GLOBALS['__error_message'][$field]);
        $GLOBALS['__error_message'][$field] = $pos + 1;
        $field .= $pos;
    }
    $error_message = $smarty->error($field);
    $tpl           = $smarty->get_template_vars('Error_Message_Template');
    
    if (!empty($error_message))
    {
        if (array_key_exists('message', $params))
        {
            $error_message = $params['message'];
        }
        if ($params['template'] && !empty($tpl))
        {
            $vars = array(
                        '{$error}', 
                        '{$field}', 
                        '{$name}'
                        );
            $vals = array(
                        $error_message, 
                        $params['field'],
                        'error-'.$params['field']
                        );
            $error = str_replace($vars, $vals, $tpl);
        } else {
            $class = empty($params['class']) ? 'error_message' : $params['class'];
            $error = '<span class="'.$class.'">' . $error_message . '</span>';
        }
        $return = $params['prefix'] . $error . $params['sufix'];
        if (empty($params['return']))
        {
            echo $return;
        } else {
            return $return; 
        }
    }
}

?>