<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

function smarty_function_render($params, &$smarty)
{
    if (isset($params['view']))                                         
    {
        $view = $params['view'];
        unset($params['view']);
        Barakus::import(Config::get("views_dir_real") . ".Renderable." . $view);
        
        $expl = explode('.', $view);
        $class = "R".array_pop($expl)."View";
        $renderable = new $class($smarty);
        foreach ($params as $param => $value)
        {
            $renderable->$param = $value;
            $smarty->assign($param, $value);
        }
        $renderable->render($params);
        $content = $renderable->show();
        $renderable->end();
        
        return $content;
    } elseif (isset($params['tpl'])) {
        $tpl = $params['tpl'];
        unset($params['tpl']);
        foreach ($params as $param => $value)
        {
            $smarty->assign($param, $value);
        }
        
        if (isset($params['cache']))
        {
            ($params['cache'] === false) ? $smarty->endCache() : $smarty->initCache();
        }                                                                     
        //$cache_id = md5(implode('|',$params));
                                                         
        $content = $smarty->fetch(Barakus::getPath(Config::get("views_dir_real") . "." . $tpl) . "." . Config::get('view_extension'), $cache_id); 
        if (isset($params['cache']))
        {
            ($params['cache'] === false) ? $smarty->initCache() : $smarty->endCache();
        }                                                                                                                              
        return $content;
    } else {
        $smarty->_trigger_fatal_error("[render] parameter 'view' or 'tpl'  cannot be empty");
        return;        
    }
}

/* vim: set expandtab: */

?>
