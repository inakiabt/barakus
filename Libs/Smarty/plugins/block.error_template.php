<?php
function smarty_block_error_template($params, $content, &$smarty)
{
    if (empty($content))
        return;
    $smarty->assign('Error_Message_Template', $content);
    return '';
}
?>
