<?php
	define('L_ERROR',    2);
	define('L_ALERT',    4);
	define('L_CRITICAL', 8);
	define('L_WARNING',  16);
	define('L_NOTICE',   32);
	define('L_INFO',     64);
	define('L_DEBUG',    128);
	define('L_ALL',      511);
    define('LOG_DATE_FORMAT', 'd-m-Y H:i:s');

	function __priority($priority)
	{
    	switch($priority)
		{
    		case 'error': return L_ERROR;
            case 'alert': return L_ALERT;
            case 'critical': return L_CRITICAL;
            case 'warning':  return L_WARNING;
            case 'notice':   return L_NOTICE;
            case 'info':     return L_INFO;
            case 'debug':    return L_DEBUG;
            case 'all':      return L_ALL;
            default: Error::InvalidLogPriority($priority);
    	}
	}

	function __callback($match)
	{
		if (empty($match[0]))
		{
			return '';
		}
		return __priority($match[0]);
	}

	class Logger
    {
    	var $observers = array();
        var $priority;
        var $config    = array();

    	function log($msg, $type = L_INFO)
        {
        	if (($this->priority & $type) == $type)
            {
	            $this->logMessage($msg, $type);
            }
            $this->notify($msg, $type);
        }
        
        function setConfig($config)
        {
            $this->config = $config;
        }

        function addObserver($logger)
        {
        	$this->observers[] = $logger;
        }

        function notify($msg, $type)
        {
        	foreach ($this->observers as $observer)
            {
            	$observer->log($msg, $type);
            }
        }

        function logMessage($msg, $type)
        {
        }

        function getTypeName($type)
        {
        	switch ($type)
            {
            	case L_ERROR: return "Error";
                case L_ALERT: return "Alert";
                case L_CRITICAL: return "Critical";
                case L_WARNING:  return "Warning";
                case L_NOTICE:   return "Notice";
                case L_INFO:     return "Info";
                case L_DEBUG:    return "Debug";
                case L_ALL:      return "All";
            }
        }

        function getPriority($prioritys)
        {
        	$prioritys = str_replace(' ', '', $prioritys);
        	$priority = 0;

        	return preg_replace_callback('/[a-z]*/i', '__callback', $prioritys);
		}
    }
?>