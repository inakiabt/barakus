<?php
    function _getJavascriptFiles($files)
    {
        $files = empty($files) ? array() : explode(',', $files);
        
        if (!is_array($GLOBALS['__js_files']))
        {
            $GLOBALS['__js_files'] = array();
        }
        if (count($files))
        {
            $files = array_merge($files, $GLOBALS['__js_files']);
        } else { 
            $files = $GLOBALS['__js_files'];
        }
        return array_unique($files);        
    }
    
    function _getEventsContent($smarty)
    {                  
        $contents = '';
        if (count($smarty->events))
        {
            foreach($smarty->events as $id => $events)
            {
                foreach($events as $event)
                {
                    $contents .= $event->showSource();
                }
            }
        }
        return $contents;
    }
    
    function _getJavascriptFilesContent($file)
    {
        $file .= '.js'; 
        
        $current_src = WEB_PATH . '/js/' . $file ;
        $barakus_src = FRAMEWORK_PATH . '/Repository/js/' . $file ;
        $contents = "\n/* FILE: $file */\n";
        if (file_exists($current_src)) 
        {
           $contents .= file_get_contents($current_src) . "\n";                   
        } elseif (file_exists($barakus_src)) 
        {
           $contents .= file_get_contents($barakus_src) . "\n";                   
        } else {
            $contents .= "\n/* FILE NOT FOUND: $file */\n";
        }               
        return $contents;
    }
    
    function _writeJavascriptCache($files, $js_cache, $cache_file, $compress, $smarty)
    {
        if (count($files) > 0)
        {
            foreach($files AS $key => $file) 
            {
               $contents .= ($file == '__EVENTS__') ? _getEventsContent($smarty) : _getJavascriptFilesContent($file);
            }        

            if($contents) 
            {
				$date = date('d-m-Y H:i:s');
				$date_title = "/* created on {$date} */\n";
                if ($fp = fopen($js_cache . $cache_file, 'w+')) 
                {
                    if (!isset($compress) || $compress)
                    {
                        Barakus::import('Barakus.Vendors.jsmin');
                        fwrite($fp, $date_title . JSMin::minify($contents));
                    } else {
                        fwrite($fp, $date_title . $contents);
                    }
                    fclose($fp);
                } 
            }        
            return true;
        }
        return false;
    }
    
    function smarty_function_js($params, &$smarty)
    {
        $params['files'] = str_replace(' ', '', $params['files']);
        $files = $sort_files = _getJavascriptFiles($params['files']);
        
        sort($sort_files);
        
        $cache_file = md5(implode('_', $sort_files)) . '.js';
        
        $js_cache = "/js/cache/";
        
        $return = '';
        
        $cache = (!isset($params['cache']) || $params['cache']);
        
        /*
        * No funcionaria debido a los scripts en el repositorio de Barakus
        if (!$cache)
        {
            $no_cache_return = '';
            foreach ($files as $file)
            {
                $file .= '.js';
                
                $no_cache_return .= _generateJavascriptTag("/js/" . $file) . "\n";
                continue;
            }
            return $no_cache_return;
        }
        */

        if (($cache && file_exists(WEB_PATH . $js_cache . $cache_file)) || 
            _writeJavascriptCache($files, WEB_PATH . $js_cache, $cache_file, $params['compress'], $smarty)) 
        {
            $return .= _generateJavascriptTag($js_cache . $cache_file);
        }
        
        $cache_file = md5(Barakus::getPage().Request::get('action').'/EVENTS/__') . '.js';
        if ($smarty->__events)
        {
            if (($cache && file_exists(WEB_PATH . $js_cache . $cache_file)) || 
                _writeJavascriptCache(array('__EVENTS__'), WEB_PATH . $js_cache, $cache_file, $params['compress'], $smarty)) 
            {
                $return .= _generateJavascriptTag($js_cache . $cache_file);
            }
        }
        return $return;
    }
    
    function _generateJavascriptTag($file)
    {
        return '<script src="' . DOCUMENT_APP . $file . '" type="text/javascript"></script>'."\n";
    }
?>
