<?php

	class ErrorController
	{
		function FatalError()
		{
			$error = Request::get('msgError');
			preg_match_all('|(.*): (.*) in (.*) on line ([0-9]*)$|U', $error, $matches, PREG_SET_ORDER);

			//$msg = stripcslashes($matches[0][2])." in ".$matches[0][3]." on line ".$matches[0][4];
			$Error = $matches[0][2];
			$File  = $matches[0][3];
			$Line  = $matches[0][4];

			$handler = Barakus::getErrorHandler();
			$handler->FatalError($Error, /*stripcslashes(*/$File/*)*/, $Line);
		}

		function ShowSource()
		{
			$app_mode = Config::get('application_mode');
			if ($app_mode == 'debug')
			{
				$file = Request::get('file');
				$line = Request::get('line');
				$backtrace = new Backtrace();
				$backtrace->ShowSource('', $file, $line, true);
			}
		}
	}
?>