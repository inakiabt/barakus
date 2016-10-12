<?php

	class ErrorView
	{
		function FatalError()
		{                             
			$error = Request::get('msgError');
			preg_match_all('|(.*): (.*) in (.*) on line ([0-9]*)$|U', $error, $matches, PREG_SET_ORDER);

			//$msg = stripcslashes($matches[0][2])." in ".$matches[0][3]." on line ".$matches[0][4];
			$Error = $matches[0][2];
			$File  = $matches[0][3];
			$Line  = $matches[0][4];

			Barakus::getErrorHandlerInstance()->FatalError($Error, /*stripcslashes(*/$File/*)*/, $Line);
		}

		function ShowSource()
		{
			$file = stripslashes(Request::get('file'));
			$backtrace = new Backtrace();
			$backtrace->showSource($file, Request::get('line'));
		}
	}
?>