<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2006
 */
 	Barakus::import("Barakus.Request.ReqMaster");
	class Server
	{
		var $__type = '_SESSION';
		function Server()
		{
//			parent::setType($this->__type);
		}
		function set($key, $value)
		{
			$_SERVER[$key] = $value;
		}
		function get($key)
		{
			return $_SERVER[strtoupper($key)];
		}
		function toArray()
		{
			return $_SERVER;
		}
		function intval($key)
		{
			$temp = intval($_SERVER[$key]);
			if ($_SERVER[$key] != '0' && !empty($_SERVER[$key]) && $temp == 0)
			{
				Error::NotIntVal($key);
			}
			return $temp;
		}
		function htmlentities($key)
		{
			return htmlentities($_SERVER[$key]);
		}
		function strip_tags($key)
		{
			return strip_tags($_SERVER[$key]);
		}
		function nl2br($key)
		{
			return nl2br($_SERVER[$key]);
		}
	}

?>