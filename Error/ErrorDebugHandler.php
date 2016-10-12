<?php

	class ErrorDebugHandler
	{
		function ErrorDebugHandler()
		{
            LibManager::import('Smarty.plugins.compiler\.addjs');
            LibManager::import('Smarty.plugins.function\.js');
            
            $view = new Smarty_Compiler();
            
            smarty_function_js(array(
                                'files' => 'prototype, scriptaculous, error',
                                'compress' => true,
                                'cache' => true
                                ), $view);
            //smarty_compiler_addjs('file="prototype"', $view);
            //smarty_compiler_addjs('file="scriptaculous"', $view);
            //smarty_compiler_addjs('file="error"', $view);
            
			ini_set('html_errors',  false);
			$PREPEND = "
			<html>
				<head>
					<title>Fatal Error</title>
                    ".smarty_function_js(array(
                                'files' => 'prototype, scriptaculous, error',
                                'compress' => true,
                                'cache' => true
                                ), $view)."
    <style type=\"text/css\">
        A {
            color: #000;
            text-decoration: none;
        }
        A:hover {
            text-decoration: underline;
        }

        div.trace {
            font-size: 10px;
            font-family: Verdana;
            padding: 2px 0 2px 2px;
            margin-bottom: 2px;
            color: #666;
            border: 1px solid #FFF;
        }

        div#trazas {
            background-color: #FFF7F2;
            border: 1px solid #C64A00;
            padding: 5px;
        }
        div#backtrace {
            margin-top: 5px;
            background-color: #FFC49F;
            border: 1px solid #C64A00;
            padding: 5px;
            font-family: Verdana;
            font-size: 14px;
            font-weight: bold;
            border-bottom: none;
        }
    </style>
				</head>
				<body>
					<div id=\"ERROR_BODY\"></div>
			        <div id=\"error\" style=\"display: none\">";
			$APPEND = "</div>
					<script type=\"text/javascript\">
					var error = $('error').innerHTML;
			        var opt = {
			            // Use POST
			            method: 'post',
			            // Send this lovely data
			            postBody: 'msgError=' + error,
			            // Handle successful response
			            onSuccess: function(t) {
			            	$('ERROR_BODY').innerHTML = t.responseText;
			            },
			            // Handle 404
			            on404: function(t) {
			                alert('Error 404: location \"' + t.statusText + '\" was not found.');
			            },
			            // Handle other errors
			            onFailure: function(t) {
			                alert('Error ' + t.status + ' -- ' + t.statusText);
			            }
			        }
			        new Ajax.Request('?BARAKUS_ERROR=FatalError', opt);
					</script>
				</body>
			</html>
			";
			ini_set('error_prepend_string', $PREPEND);
			ini_set('error_append_string',  $APPEND);
			if (PHP_VERSION >= 5)
			{
			 	set_error_handler(array("ErrorDebugHandler", "Handler"), E_ALL);
                set_exception_handler(array("ErrorDebugHandler", "ExceptionHandler"));
			} else {
			 	set_error_handler(array("ErrorDebugHandler", "Handler"));
			}
		}
        
        function ExceptionHandler(Exception $exception)
        {
            ErrorDebugHandler::Handler(E_EXCEPTION, $exception->getMessage(), $exception->getFile(), $exception->getLine(), null, $exception->getTrace());
        }        

		function Handler($type, $msg, $file, $line, $context, $debug = null)
		{
			if (error_reporting() != 0 && ((E_ERROR_SUPPORTED & $type) != $type))
			{
                if (Config::get('on_error_controller') != '')
                {
                    Request::set('page',   Config::get('on_error_controller'));
                    Request::set('action', 'onError');
                    $actionController = new ActionController();
                    $actionController->doAction(false);
                }

				ob_clean();

				$backtrace = ErrorDebugHandler::getBacktrace();
				$backtrace->Debug($type, $msg, $file, $line, $context, $debug);
	        }
		}

		function FatalError($msg, $file, $line)
		{
            if (Config::get('on_error_controller') != '')
            {
                Request::set('page',   Config::get('on_error_controller'));
                Request::set('action', 'onError');
                $actionController = new ActionController();
                $actionController->doAction(false);
            }
            
            ob_clean();

			$backtrace = ErrorDebugHandler::getBacktrace();
			$backtrace->Debug(0, $msg, $file, $line, array());
		}

		function getBacktrace()
		{
			static $backtrace;

			if (!$backtrace)
			{
				$backtrace = new Backtrace;
			}
			return $backtrace;
		}
	}

?>