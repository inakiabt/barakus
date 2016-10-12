<?php
    function smarty_function_css($params, &$smarty)
    {
        $params['files'] = str_replace(' ', '', $params['files']);
        
        $files = _getCSSFiles($params['files']);
        
        $cache_file = md5($params['files']) . '.css';

        $css_path = "/css/cache/";
        $css_cache_path = WEB_PATH . $css_path;
        
        $cache = (!isset($params['cache']) || $params['cache'] == true);
        if ($cache && file_exists($css_cache_path . $cache_file)) 
        {
            return _generateCSSTag($css_path . $cache_file);
        }       
        
        $contents = '';
        
        $no_cache_return = '';
        
        foreach($files as $key => $file) 
        {
            $file .= '.css';
            
            if (!$cache) 
            {
                $no_cache_return .= _generateCSSTag("/css/" . $file) . "\n";
                continue;
            }            
            
            $current_src = WEB_PATH . '/css/' . $file ;
            if (file_exists($current_src)) 
            {
               $contents .= file_get_contents($current_src) . "\n";        
               
               $contents = preg_replace('@url\(["|\']?((\/img\/)[^"|\'|\)]*)["|\']?\)@', 'url(\''.DOCUMENT_APP.'$1\')', $contents);
            } else {
                $contents .= "\n/* FILE NOT FOUND: $file */\n";
            }               
        }        

        if(!empty($contents)) 
        {
            if ($fp = fopen($css_cache_path . $cache_file, 'wb')) 
            {
                if (!isset($params['compress']) || $params['compress'])
                {
                    Barakus::import('Barakus.Vendors.jsmin');
                    fwrite($fp, JSMin::minify($contents));
                } else {
                    fwrite($fp, $contents);
                }
                fclose($fp);
            } 
        }      
        
        if (!$cache)
        {
            return $no_cache_return;
        }  
        
        return _generateCSSTag($css_path . $cache_file);
    }
    
    function _generateCSSTag($file)
    {
        return '<link href="' . DOCUMENT_APP . $file.'" type="text/css" rel="stylesheet" />';
    }
    
    function _getCSSFiles($files)
    {
        $files = empty($files) ? array() : explode(',', $files);
        
        if (!is_array($GLOBALS['__css_files']))
        {
            $GLOBALS['__css_files'] = array();
        }
        if (count($files))
        {
            $files = array_merge($files, $GLOBALS['__css_files']);
        } else { 
            $files = $GLOBALS['__css_files'];
        }
        return array_unique($files);        
    }    
?>
