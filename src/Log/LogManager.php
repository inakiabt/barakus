<?php
   	Barakus::import('Barakus.Log.Logger');
   	Barakus::import('Barakus.Log.FileLogger');

	class LogManager
    {
        private static $instance = null;
    	public static function getInstance()
        {
            if (self::$instance == null)
            {
                $log_config = Config::get('log_config');

            	if (Config::get('log_errors') == "true")
                {
                	$log_first = LogManager::getConfigDefault(Config::get('log_type'));
                } elseif (is_array($log_config)) {
	                $log_first  = $log_config[0];
		            array_shift($log_config);
				}

	            self::$instance = LogManager::__instance($log_first['type'], $log_first);
                self::$instance->setConfig($log_first);
	            if (is_array($log_config))
	            {
		            foreach ($log_config as $config)
		            {
                        $new_log = LogManager::__instance($config['type'], $config);
                        $new_log->setConfig($config);
		                self::$instance->addObserver($new_log);
		            }
                }
            }
            return self::$instance;
        }

        function log($msg, $type)
        {
            $log = LogManager::getInstance();
            if ($log)
            {
            	$log->log($msg, $type);
            }
        }

        function getConfigDefault($type)
        {
        	$config             = array();
           	$config['type']     = $type;
            $config['priority'] = L_ERROR;
        	switch ($type)
            {
            	case 'file':
                	$config['file'] = FileLogger::getFileDefault();
                    $config['path'] = FileLogger::path();
                    if (!file_exists(Barakus::getPath($config['path'])))
                    {
                    	if (!mkdir(Barakus::getPath($config['path']), 0775))
                        {
                        	Error::CantCreateDir(Barakus::getPath($config['path']));
                        }
                    }
	                $config['maxfiles'] = 15;
	                $config['maxsize']  = 2024;

                    break;
            	case 'mail':
                	Error::LogErrorMailTypeNotAllowed();
                    break;
            }
            return $config;
        }

        function __instance($type, $config)
        {
        	switch ($type)
            {
            	case 'file':
            		if (empty($config['file']))
            		{
            			$config['file'] = FileLogger::getFileDefault();
            		}
                	Barakus::import('Barakus.Log.FileLogger');
                	return new FileLogger($config['file'], Barakus::getPath($config['path']), $config['priority'], $config['maxfiles'], $config['maxsize']);
                    break;
            	case 'db':
                	Barakus::import('Barakus.Log.DBLogger');
                    $manager = new DBManager();
                	return new DBLogger($manager->getInstance('adodb'));
                    break;
            	case 'console':
                	Barakus::import('Barakus.Log.ConsoleLogger');
                	return new ConsoleLogger;
                    break;
            	case 'mail':
                	Barakus::import('Barakus.Log.MailLogger');
                	return new MailLogger($param, Config::get('log_email_from'), Config::get('log_email_subject'));
                    break;
            	case 'display':
                	Barakus::import('Barakus.Log.DisplayLogger');
                	return new DisplayLogger($param);
                    break;
            }
        }
    }
?>