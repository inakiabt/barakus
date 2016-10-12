<?php

function smarty_outputfilter_layout($tpl_output, &$smarty)
{
    pr($smarty, 1);
    $smarty->assign('LAYOUTVAR_' . $smarty->_last_tpl['id'], $tpl_output);
    $smarty->unregister_outputfilter('layout');
    return $smarty->_last_tpl['tpl'];//$smarty->fetch($smarty->__createTemplateFile($smarty->_last_tpl['tpl']));
}
?>