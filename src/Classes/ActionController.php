<?php

	class ActionController
	{
		function doAction($request = true)
		{
            
			$import   = Request::get('BARAKUS_PLUGIN');
			$error    = Request::get('BARAKUS_ERROR');

			if ($request && !empty($error))
			{
				Barakus::import("Barakus.Error.ErrorController");
				$Error = new ErrorController();
                if (method_exists($Error, $error))
                {
					$Error->$error();
                } else {
                    Error::InvalidAction($error);
                }

				return;
			} elseif ($request && !empty($import))
			{
                if (file_exists(Barakus::getPath(Config::get("plugins_dir_real") . ".Controller." . $import) . ".php"))
                {
                    Barakus::import(Config::get("plugins_dir_real") . ".Controller." . $import);
                } elseif (file_exists(Barakus::getPath("Barakus.Plugins." . $import) . ".php")) {
                    Barakus::import("Barakus.Plugins." . $import);
                } else {
                    Error::FileNotFound(Barakus::getPath(Config::get("plugins_dir_real") . ".Controller." . $import) . ".php");
                }
				$page = $class = $import;

			} else {
				$page = $class = Request::get('page');
                /*
                pr($_POST);
                echo "($page)($action)";
                exit();
                */
				if (empty($page))
				{
					$page = $class = Config::get('DefaultPage');
					$action = Config::get('DefaultAction');

					Request::set('page',   $page);
					Request::set('action', $action);
				} else {
					$action   = Request::get('action');
				}
                $cant = is_array(Config::get("controller_dirs")) ? count(Config::get("controller_dirs")) : 0;
                $founded = false;
                if ($cant > 0)
                {
                    foreach (Config::get("controller_dirs") as $dir)
                    {
                        if (file_exists(Barakus::getPath($dir['dir'] . "." . $page) . ".php"))
                        {
                            Barakus::import($dir['dir'] . "." . $page);
                            $founded = true;
                            break;
                        }                            
                    }
                }
                if (!$founded)
                {
                    if (file_exists(Barakus::getPath(Config::get("controller_dir_real") . "." . $page) . ".php"))
                    {
                        Barakus::import(Config::get("controller_dir_real") . "." . $page);
                    }                        
                }
			}
            
            if (preg_match('@\.@', $class))
            {
                $expl = explode('.', $class);
                $class = $expl[1];
            }
            if (class_exists($class))
            {
			 	ob_start();
            	$this->pageLifeCycle($class, $page, $action, Post::get('ajaxCall.MD5') == md5($page));
            	ob_end_flush();
            } elseif (file_exists(Barakus::getPath(Config::get("views_dir_real") . "." . $page . "." . strtolower($action)) . "." . Config::get('view_extension'))) {
                $this->displayView($page . '.' . strtolower($action));
            } elseif (file_exists(Barakus::getPath(Config::get("views_dir_real") . "." . $page . ".index") . "." . Config::get('view_extension'))) {
                $this->displayView($page . ".index");
            } else {
                Error::ClassNotExists($page);
            }
            
		}
        function displayView($page)
        {            
            ob_start();
            $view = new View();
            $view->load($page);
            $view->setController(new Controller());
            $view->assign('URL_BASE', DOCUMENT_APP);
            $view->show();
            ob_end_flush();
        }
		function pageLifeCycle($clase, $page, $action, $isAJAXCallBack = false)
		{
            
            $controller = new $clase();       
            $className = Config::get($clase);

            $controller->setIsAJAXCallBack($isAJAXCallBack);     
            $controller->preLoad(empty($className) ? $clase : $className);
                                                                      
            $controller->onLoad(); 
            
                                                             
            if (!empty($action))
            {
                $post_action = 'post_' . $action;
                if ($isAJAXCallBack || !Request::isPost() || !$this->performAction($controller, $post_action, $isAJAXCallBack))
                {
                    if (!Request::isPost() && preg_match('/^(post_|ajax_|validation_|prevalidation_|postvalidation_|private_|prevalidation$)/', $action))
                    {
                        Error::Fire('Esta solicitando una Accion invalida: ' . $action . '');
                    }
                    if (!$this->performAction($controller, $action, $isAJAXCallBack))
                    {
                        if (file_exists(Barakus::getPath(Config::get("views_dir_real") . "." . $page . "." . strtolower($action)) . "." .Config::get('view_extension'))) 
                        {
                            $this->displayView($page . "." . strtolower($action));
                            exit();
                        } else {
                            Error::InvalidAction($action);
                        }
                    }
                }
            } else {
                $controller->index(); 
            }
            $controller->end();
		}
        function performAction($object, $action, $isAJAXCallBack)
        {
            if (!$isAJAXCallBack)
            {                            
                if (method_exists($object, $action))
                {
                    $object->$action();     
                    return true;
                }
            } else {
                $ajax_action = 'ajax_' . $action;
                if (method_exists($object, $ajax_action))
                {
                    $object->$ajax_action(new RequestSender());
                    exit();
                    return true;
                }
            }
            return false;
        }
	}

?>