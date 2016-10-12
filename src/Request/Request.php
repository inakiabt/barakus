<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2006
 */
 	//Barakus::import("Barakus.Request.ReqMaster");
	class Request
	{
		var $__type = '_REQUEST';
        
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
			$_REQUEST[$key] = $value;
		}
		public static function get($key, $default = null)
		{
			if (!is_null($default) && empty($_REQUEST[$key]))
			{
				return $default;
			}
			return $_REQUEST[$key];
		}
		public static function toArray()
		{
			return $_REQUEST;
		}
		public static function intval($key)
		{
			$temp = intval($_REQUEST[$key]);
			if ($_REQUEST[$key] != '0' && !empty($_REQUEST[$key]) && $temp == 0)
			{
				Error::NotIntVal($key);
			}
			return $temp;
		}
		public static function htmlentities($key)
		{
			return htmlentities($_REQUEST[$key]);
		}
		public static function strip_tags($key)
		{
			return strip_tags($_REQUEST[$key]);
		}
        public static function strip_path($key)
        {
            $get = Request::get($key);
            
            return str_replace(array("/", "\\", ".."), "", $get);
        }
		public static function nl2br($key)
		{
			return nl2br($_REQUEST[$key]);
		}
		public static function Id()
		{
			return session_id();
		}
        public static function Sender()
        {
            return $_SERVER['HTTP_REFERER'];
        }
        public static function getSenderAction()
        {
            $sender = Request::Sender();
            preg_match('/&action=(.*)(&.)?/', $sender, $action);
            return $action[1];
        }
        public static function getUrl()
        {
            return str_replace(DOCUMENT_APP, '', $_SERVER['REQUEST_URI']);
        }
        public static function isPost()
        {
            return Server::get('REQUEST_METHOD') == 'POST' && eregi('form', Server::get('CONTENT_TYPE'));
        }
        public static function getVars()
        {
            preg_match_all('@\??([0-9a-zA-Z_\-\.\[\]]*)=([0-9a-zA-Z_\-\. ]*)&?@', Request::getUrl(), $matches);
            $vars = array();
            foreach ($matches[1] as $i => $var)
            {
                $vars[$var] = $matches[2][$i];
            }
            return $vars;
        }     
        public static function ip()
        {
            if (!empty($_SERVER['HTTP_CLIENT_IP']))
            {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
            {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            }
            else
            {
                $ip = $_SERVER['REMOTE_ADDR'];
            }
            return $ip;            
        }
	}

?>