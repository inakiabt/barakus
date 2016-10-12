<?php
	class Session
	{
		var $__type = '_SESSION';
		function Session()
		{
//			parent::setType($this->__type);
		}
		function notEmpty($key)
		{
			$value = Session::get($key);

			return !empty($value);
		}
		function set($key, $value)
		{
			if (eregi("\.", $key))
			{
				$expl  = explode(".", $key);

				Session::__SetRecursiveArray($expl, 0, $_SESSION, $value);
			} else {
			    $_SESSION[$key] = $value;
            }
		}
        function __SetRecursiveArray($keys, $i, &$session, $dato)
        {
			if ($i < count($keys))
			{
				Session::__SetRecursiveArray($keys, $i + 1, $session[$keys[$i]], $dato);
			} else {
			    $session = $dato;
            }
		}
		function get($key)
		{
			if (eregi("\.", $key))
			{
				$expl  = explode(".", $key);

				return Session::__GetRecursiveArray($expl, 0, $_SESSION);
			}
			return $_SESSION[$key];
		}
		function __GetRecursiveArray($keys, $i, $sess)
		{
			if ($i < count($keys))
			{
				$s = $sess[$keys[$i]];
				return Session::__GetRecursiveArray($keys, $i + 1, $s);
			}
			return $sess;
		}
		function &toArray()
		{
			return $_SESSION;
		}
		function intval($key)
		{
            $value = Session::get($key);
			$temp = intval($value);
			if ($_SESSION[$key] != '0' && !empty($_SESSION[$key]) && $temp == 0)
			{
				Error::NotIntVal($key);
			}
			return $temp;
		}
		function htmlentities($key)
		{
			return htmlentities($_SESSION[$key]);
		}
		function strip_tags($key)
		{
			return strip_tags($_SESSION[$key]);
		}
		function nl2br($key)
		{
			return nl2br($_SESSION[$key]);
		}
		function Id()
		{
			return session_id();
		}
		function reset($key)
		{
            if (eregi("\.", $key))
            {
                $expl  = explode(".", $key);
                $unset = array_pop($expl);
                $nkey  = implode('.', $expl);
                
                $item = Session::get($nkey);
                unset($item[$unset]);
                Session::set($nkey, $item);
            } else {
                unset($_SESSION[$key]);
            }
        }
	}

?>