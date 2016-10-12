<?php                                    
function smarty_block_option($params, $content, &$smarty)
{
    if (!empty($content) || $content == '0')
    {
        $notValid = intval($smarty->get_template_vars('ValidationFailed')) || !intval($smarty->get_template_vars('FormEdit'));
        if ($notValid)
        {
            $value     = $smarty->get_template_vars('SelectOptionValue');  
        } else {
            $value     = $params['value'];
        }
             
        if (($notValid && $value == $params['value']) || (!$notValid && $params['sel'] == $value))
        {
            $params['selected'] = 'selected';   
        }
        $params['label'] = $content;
        echo ViewHelper::option($params);
        echo $content;
        echo "</option>\n";
    }
}     
?>
