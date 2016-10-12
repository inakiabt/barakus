<?PHP /* $Id: function.textarea.php,v 1.1.1.1 2002/09/13 17:52:11 darkelder Exp $ */

function smarty_function_flash($params, &$smarty)
{
    if (empty($params['file'])) 
    {
        $smarty->_trigger_fatal_error("[flash] parameter 'file' cannot be empty");
        return;
    }
    
    $params['src']   = $params['file'];
    unset($params['file']);
    
    $extras = $params['extras'];
    
    $noscript = $params['noscript'];
    unset($params['extras'], $params['noscript']);
        
    $obj_atts = array('classid', 
                      'codebase', 
                      'data', 
                      'type', 
                      'codetype', 
                      'archive',
                      'standby',
                      'height',
                      'width',
                      'usemap',
                      'align',
                      'name',
                      'tabindex',
                      'id');
    $embed = array();
    $js            = '';
    $object_atts   = '';
    $object_params = "<param name=\"movie\" value=\"".$params['src']."\" />";
    $embed = "type=\"application/x-shockwave-flash\"\n\tpluginspage=\"http://www.macromedia.com/go/getflashplayer\" ";
    $js .= "'pluginspage', 'http://www.macromedia.com/go/getflashplayer', ";
    if (!array_key_exists('codebase', $params))
    {
        $js          .= "'codebase', 'http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0', ";
        $object_atts .= "codebase=\"http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0\" ";
    }
    if (!array_key_exists('classid', $params))
    {
        $js          .= "'classid', 'clsid:d27cdb6e-ae6d-11cf-96b8-444553540000',";
        $object_atts .= "classid=\"clsid:d27cdb6e-ae6d-11cf-96b8-444553540000\"";
    }
    
    $total = count($params);
    $i     = 1;
    foreach ($params as $param_name => $value)
    {
        $embed .= "$param_name=\"$value\" ";
        if ($param_name == 'src')
        {
            $value = preg_replace('|\.swf|i', '', $value);
        }
        $js    .= "'$param_name', '$value'";
        if (in_array($param_name, $obj_atts))
        {
            $object_atts   .= "$param_name=\"$value\" ";
        } else {
            $object_params .= "<param name=\"$param_name\" value=\"$value\" />";
        }
        if ($i < $total)
        {
            $js            .= ", ";
            $object_params .= " ";
        }
        $i++;
    }
        
    if (!$noscript)
    {
        echo "<script type=\"text/javascript\">AC_FL_RunContent(" . $js . ");</script><noscript><object " . $object_atts . ">" . $object_params . $extras;
    }
    echo "<embed " . $embed . " />";
    if (!$noscript)
    {
        echo "</object></noscript>";
    }
}

?>