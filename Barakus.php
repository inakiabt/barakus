<?php
    ini_set("session.use_cookies",   "on"); 
    ini_set("session.use_trans_sid", "off");  
    
    include_once('defines.php');

    $GLOBALS['__barakus']['paths']['Barakus'] = array('path' => FRAMEWORK_PATH, 'remove-prefix' => true);
    $GLOBALS['__barakus']['paths']['Application'] = array('path' => APP_PATH, 'remove-prefix' => false);
    $GLOBALS['__barakus']['paths']['root'] = array('path' => WEB_PATH, 'remove-prefix' => true);
    
    if (!defined('CONFIGURATION_FILE'))   
    {
        define('CONFIGURATION_FILE', Barakus::getPath("Application") . "/configuration.xml");
    }
    
 	session_start();
    
    set_include_path(get_include_path() . PATH_SEPARATOR . Barakus::getPath('Barakus.Libs.PEAR'));
    
    Barakus::import("Barakus.Helpers.FunctionsHelper");
    Barakus::import("Barakus.Log.LogManager");
    Barakus::import("Barakus.Error.Error");
    Barakus::import("Barakus.Error.Backtrace");

	if (!file_exists(APP_PATH . "/Application"))
	{
		Error::ApplicationDirNotExists();
	}

	Barakus::import("Barakus.Libs.LibManager");
	LibManager::import("Smarty.Smarty\.class");

	Barakus::import("Barakus.Config.*");
	Barakus::import("Barakus.Classes.*");
    Barakus::import("Barakus.Request.*");
    Barakus::import("Barakus.Events.EventManager");    

	$locale_lang = Config::get("locale_lang");
	if (!empty($locale_lang))
	{
		if (!($LLocale = setlocale(LC_TIME, $locale_lang)))
		{
			LogManager::log("No se pudo setear el Locale", L_WARNING);
		}
	}

	$app_mode = Config::get('application_mode');  
    Barakus::ChangeApplicationMode($app_mode);

	class Barakus
	{
		var $Request;
		var $Session;
		var $DefaultConfig;

		function Barakus()
		{
			//$this->Request = new Request;
			//$this->Session = new Session;
			//$this->DefaultConfig = new BarakusConfig();
		}
		function init()
		{
			$this->ImportDBManager();
			$this->Pages();

            $includes = Config::get('includes');
            if (count($includes))
            {
                foreach ($includes as $include)
                {
                    Barakus::import($include);
                }
            }
            
			$controller = new ActionController;
			$controller->doAction();
		}
        
        public static function showConstants()
        {
            Barakus::import('Barakus.constants');
        }

		public static function import($file, $return = false, $include_once = false)
		{
			$file = Barakus::getPath($file);
			if (substr($file, -1) == '*')
			{
				$dir = substr($file, 0, -2);
				if (is_dir($dir))
				{
					Barakus::import_all($dir, $return);
				} else {
					if (!$return)
					{
						Error::FileNotFound($file);
					} else {
						return false;
					}
				}
			} else {
				$file .=  '.php';
				if (file_exists($file))
				{
                    if (!$include_once)
                    {
					    require_once($file);
                    } else {
                        include_once($file);
                    }
				} else {
					if (!$return)
					{
						Error::FileNotFound($file);
					} else {
						return false;
					}
				}
			}
			return true;
		}
        public static function addPath($prefix, $path, $remove_prefix = true)
        {
            $GLOBALS['__barakus']['paths'][$prefix] = array('path' => $path, 'remove-prefix' => $remove_prefix);
        }
		public static function getPath($path)
		{
			$path = str_replace("\.", "\*", $path);
			$expl = explode(".", $path);
            
            $abspath = $GLOBALS['__barakus']['paths'][$expl[0]]['path'];
            if (empty($abspath))
            {
                Error::Fire("Prefix not exists: '{$expl[0]}' on {$path}");
            }
            
            if ($GLOBALS['__barakus']['paths'][$expl[0]]['remove-prefix'])
            {
                array_shift($expl);
            }
            
            $ret = str_replace('\\', '/', $abspath) . '/' . implode("/", $expl);
			return str_replace(array("\*", "//"), array(".", "/"), $ret);
		}
        public static function getApplicationPath($path)
        {
            $path = str_replace("\.", "\*", $path);
            return str_replace("\*", ".", str_replace('\\', '/', APP_PATH) . '/' . str_replace(".", "/", $path));
        }
        public static function getWebPath($path)
        {
            $path = str_replace("\.", "\*", $path);
            return str_replace("\*", ".", APP_URL . str_replace(".", "/", $path));
        }
        public static function getAbsoluteWebPath($path)
        {
            return DOCUMENT_APP . $path;
        }
		public static function import_all($dir, $return = false)
		{
			if ($fd = opendir($dir))
			{
			   while (($archivo = readdir($fd)) !== false)
			   {
			   		if ($archivo != '.' && $archivo != '..' && $archivo != '.svn')
					{
			   			if (is_dir($dir . '/' . $archivo))
						{
			   				return Barakus::import_all($dir . '/' . $archivo, $return);
			   			} else {
							require_once($dir . '/' . $archivo);
						}
			   		}
			   }
			   closedir($fd);
			   return true;
			} else {
				if (!$return)
				{
					Error::CantOpenDir($dir);
				} else {
					return false;
				}
			}
		}
		public static function ImportDBManager()
		{
			$manager = Config::get("DBManager");

			if (!empty($manager))
			{
				Barakus::import("Barakus.DB.DBManager");
			}
		}
		public static function Pages()
		{

		}
        public static function getPage()
        {
            return Request::get('page');
        }
        public static function getAction()
        {
            return Request::get('action');
        }
		public static function lastDeclaredClass()
		{
			$array = get_declared_classes();
			if (!is_array($array))
			{
				return $array;
			}
			if (!count($array))
			{
				return null;
			}
			end($array);
			return $array[key($array)];
		}

        public static function ChangeApplicationMode($app_mode)
        {
			$a = Barakus::getErrorHandler(true, $app_mode);
        }

        public static function getErrorHandler($renew = false, $app_mode = '')
        {
        	static $ErrorInstance;
            
        	if (!$ErrorInstance || $renew)
        	{
        		if (!empty($app_mode))
        		{
        			$app_mode = Config::get('application_mode');
        		}

        		if ($ErrorHandlerClassName = Barakus::__getErrorHandlerTypeName($app_mode))
                {
        		    Barakus::import("Barakus.Error." . $ErrorHandlerClassName);      
        		    $ErrorInstance         = new $ErrorHandlerClassName;
                }
        	}

        	return $ErrorInstance;
		}

		public static function __getErrorHandlerTypeName($app_mode)
		{
    		switch($app_mode)
			{
    			case 'debug':
    				return 'ErrorDebugHandler';
    			case 'online':
    				return 'ErrorOnlineHandler';
    			case 'debug-low':
                    return false;
                default: 
                    return 'ErrorDebugHandler';
    		}
		}
	}

?>