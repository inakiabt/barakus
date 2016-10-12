<?php
    function smarty_function_control($params, &$smarty)
    {
        $class = $params['__class'];
        unset($params['__class']);
        Barakus::import(Config::get("plugins_dir_real") . ".View." . $class);
        $plugin = new $class($params, $smarty);
        return $plugin->plug();
    }
?>
