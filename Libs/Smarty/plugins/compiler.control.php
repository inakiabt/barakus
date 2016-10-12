<?php
    function smarty_compiler_control($params, &$smarty)
    {
        $params = $smarty->_parse_attrs($params);
        foreach ($params as $key => $value)
        {
            $params[$key] = $smarty->_dequote($value);
        }
        $class = $params['__class'];
        unset($params['__class']);
        Barakus::import(Config::get("plugins_dir_real") . ".View." . $class);
        
        $plugin = new $class($params, $smarty->_tpl_vars['this']);
        return $plugin->plug();
    }
?>
