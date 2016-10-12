<?php

class Ajax
{
	function send($str)
	{
		header("HTTP/1.1 200 OK");
		header('Pragma: no-cache');
		header('Expires: -1');
		header('Cache-Control: private');
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header('Cache-Control: max-stale=0');
		header('Cache-Control: post-check=0');
		header('Cache-Control: pre-check=0');
		header("Content-Length: ".((string)strlen($str)));
		header('Keep-Alive: timeout=3, max=993');

		echo $str;
	}
	function sendJSON($str)
	{
		header("X-JSON: $str");
		//secho $str;
	}
}

?>