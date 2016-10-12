<?php
    function barakus_cache_handler($action, &$smarty, &$cache_content, $tpl_file=null, $cache_id=null, $compile_id=null, $exp_time=null)
    {
        $_auto_id = $smarty->_get_auto_id($cache_id, $compile_id);
        $_cache_file = $smarty->_get_auto_filename($smarty->cache_dir, $tpl_file, $_auto_id);
        echo "reading cache file: $_cache_file<br>";
        $cache_content = $smarty->_read_file($_cache_file);        
    }
?>
