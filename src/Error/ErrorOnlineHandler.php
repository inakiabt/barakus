<?php

	class ErrorOnlineHandler
	{
		function ErrorOnlineHandler()
		{             
			ini_set('error_prepend_string', '');
			ini_set('error_append_string',  '');

		 	set_error_handler(array("ErrorOnlineHandler", "Handler"));
            if (PHP_VERSION >= 5)
            {
                set_exception_handler(array("ErrorOnlineHandler", "ExceptionHandler"));
            } 
		}

        public static function ExceptionHandler(Exception $exception)
        {
            ErrorOnlineHandler::Handler(E_EXCEPTION, $exception->getMessage(), $exception->getFile(), $exception->getLine(), null);
        }
        
        function Handler($type, $msg, $file, $line, $context)
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
                LogManager::log("[" . Error::getType($type) . "] $msg in $file in line $line", L_ERROR);
                LogManager::log(print_r(array_slice(debug_backtrace(), 0, 3), true), L_ERROR);

				ErrorOnlineHandler::__show();
                exit();
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
            LogManager::log("[" . Error::getType(0) . "] $msg in $file in line $line", L_ERROR);
                
			ErrorOnlineHandler::__show();
		}

		function __show()
		{
			$tpl  = Config::get("internal_online_error_page");
			if (!empty($tpl))
			{
                $tpl = str_replace('root.view.', '', $tpl);
                Request::set('page', 'Erroronline');
                Request::set('action', 'online');
                
                $actionController = new ActionController();
                $actionController->doAction();
				//$view = new View();
				//$view->show($tpl);
	        	die();
			}
		}
	}

?>