<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2006
 */

    class Config
    {
        private static $config = null;
        
        public static function set($param, $value)
        {
            self::$config[$param] = $value;
        }
        
        public static function getInstance()
        {
            if (self::$config == null)
            {
                self::$config = ConfigGenerator::getValidConfig();  
            }
            return self::$config;
        }
        
        public static function get($param = '')
        {
            if (empty($param))
            {
                Error::GetParamConfigEmpty();
            }
            
            $config = Config::getInstance();
            
            return $config[$param];
        }
        public static function toArray()
        {
            return is_null(self::$config) ? array() : self::$config;
        }
    }
?>