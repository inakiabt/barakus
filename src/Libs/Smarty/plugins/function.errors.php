<?PHP 

/*
 * Form Plugin - function error_message
 * -------------------------------------------------------------
 */

function smarty_function_errors($params,&$smarty)
{
    $errors = $smarty->errors();
    $tpl    = $smarty->get_template_vars('Error_Message_Template');
    
    if (count($errors))
    {
        foreach ($errors as $error)
        {
            if (!empty($tpl))
            {
                $vars = array(
                            '{$error}', 
                            '{$field}', 
                            '{$name}'
                            );
                $vals = array(
                            $error['message'], 
                            $error['field'],
                            'error-'.$error['field']
                            );
                echo str_replace($vars, $vals, $tpl);
            } else {
                echo '<span class="error_message">' . $error['message'] . '</span>'.$params['separator'];
            }
        }
    }
}

?>