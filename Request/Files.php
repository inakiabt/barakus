<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2006
 */
    class Files
    {
        var $__type = '_FILES';
        function __get($key)
        {
            return self::get($key);
        }
        
        function __set($key, $value)
        {
            self::set($key, $value);
        }        
        
        function set($key, $value)
        {
            $GLOBALS['__FILES'] = Files::format($_FILES);
            if (eregi("\.", $key))
            {
                $expl  = explode(".", $key);

                Files::__SetRecursiveArray($expl, 0, $GLOBALS['__FILES'], $value);
            } else {
                $GLOBALS['__FILES'][$key] = $value;
            }
        }
        function __SetRecursiveArray($keys, $i, &$post, $dato)
        {
            if ($i < count($keys))
            {
                Files::__SetRecursiveArray($keys, $i + 1, $post[$keys[$i]], $dato);
            } else {
                $post = $dato;
            }
        }
        function get($key)
        {
            $GLOBALS['__FILES'] = Files::format($_FILES);
            if (eregi("\.", $key))
            {
                $expl  = explode(".", $key);

                return Files::__GetRecursiveArray($expl, 0, $GLOBALS['__FILES']);
            }                                
            return $GLOBALS['__FILES'][$key];
        }        
        function __GetRecursiveArray($keys, $i, $sess)
        {
            if ($i < count($keys))
            {
                $s = $sess[$keys[$i]];
                return Files::__GetRecursiveArray($keys, $i + 1, $s);
            }
            return $sess;
        }        
        function format( $files, &$keys = array(), $file_key = NULL)
        {
            $names = array( 'name' => 'name', 'type' => 'type', 'tmp_name' => 'tmp_name', 'error' => 'error', 'size' => 'size' );
            foreach( $files as $key => $part)
            {
                $key = (string) $key;
                if(in_array($key, $names))
                {
                    $file_key = $key;
                    self::format($part, $keys, $file_key);
                } else {
                    if (!is_array($keys[$key]))
                    {
                        $keys[$key] = array();
                    }
                    $_keys = array_keys($part);
                    $last = $part[$_keys[0]];
                    if(is_array($last))
                    {
                        self::format($part, $keys[$key], $file_key);
                    } else
                    {
                        if (is_null($file_key))
                        {
                            $keys[$key] = $part;
                            break;
                        }
                        foreach ($part as $i => $data)
                        {
                            $keys[$key][$i][$file_key] = $data;
                        }
                    }
                }
            }
            
            return $keys;
        }      
        function toArray()
        {
            return Files::format($_FILES);
        }
        function hasFiles()
        {
            return count($_FILES) > 0;
        }
    }

?>