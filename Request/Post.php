<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2006
 */
 	Barakus::import("Barakus.Request.ReqMaster");
	class Post
	{
		var $__type = '_POST';
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
			if (eregi("\.", $key))
			{
				$expl  = explode(".", $key);

				Post::__SetRecursiveArray($expl, 0, $_POST, $value);
			} else {
			    $_POST[$key] = $value;
            }
		}
		function __SetRecursiveArray($keys, $i, &$post, $dato)
		{
			if ($i < count($keys))
			{
				Post::__SetRecursiveArray($keys, $i + 1, $post[$keys[$i]], $dato);
			} else {
			    $post = $dato;
            }
		}
		function get($key)
		{
			if (eregi("\.", $key))
			{
				$expl  = explode(".", $key);

				return Post::__GetRecursiveArray($expl, 0, $_POST);
			}
			return $_POST[$key];
		}
		function __GetRecursiveArray($keys, $i, $sess)
		{
			if ($i < count($keys))
			{
				$s = $sess[$keys[$i]];
				return Post::__GetRecursiveArray($keys, $i + 1, $s);
			}
			return $sess;
		}
		function toArray()
		{
			return $_POST;
		}
		function intval($key)
		{
			$temp = intval($_POST[$key]);
			if ($_POST[$key] != '0' && !empty($_POST[$key]) && $temp == 0)
			{
				Error::NotIntVal($key);
			}
			return $temp;
		}
		function htmlentities($key)
		{
			return htmlentities($_POST[$key]);
		}
		function strip_tags($key)
		{
			return strip_tags($_POST[$key]);
		}
		function nl2br($key)
		{
			return nl2br($_POST[$key]);
		}
		function Id()
		{
			return session_id();
		}
	}

?>