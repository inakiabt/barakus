<?php
/**
Usage
$template = new Smarty();
// do stuff
$template->load_filter('output', 'move_to_head');
// do stuff
$template->display($filename);

* outputfilter adds content of {head}-blocks to document<head>
* @param string
* @param Smarty
* @return string
*/
function smarty_outputfilter_move_to_head($tpl_output, &$smarty)
{
    //APARECIO UN BARDO IMPORTANTE DE MEMORIA CON MUCHO CONTENIDO
    $matches = array();
    preg_match_all('!@@@SMARTY:HEAD:BEGIN@@@(.*?)@@@SMARTY:HEAD:END@@@!is', $tpl_output, $matches);
	
	if (!eregi('<head>', $tpl_output))
	{
		$tpl_output = preg_replace("!@@@SMARTY:HEAD:BEGIN@@@(.*?)@@@SMARTY:HEAD:END@@@!is", "<head>\n</head>", $tpl_output); 		
	} else {
		$tpl_output = preg_replace("!@@@SMARTY:HEAD:BEGIN@@@(.*?)@@@SMARTY:HEAD:END@@@!is", '', $tpl_output); 
	}
    return str_replace('</head>', implode("\n", array_unique($matches[1]))."\n".'</head>', $tpl_output);
}
?>