<?php
	class FileLogger extends Logger
    {
    	var $file;
        var $original;
        var $regexp;
        var $path;
        var $maxfiles;
        var $maxsize; //KBytes
        var $index = 0;
        var $first = 0;
        var $maxdigits = 5;

        function FileLogger($file, $path, $priority = L_ALL, $maxfiles = 1, $maxsize = 1000)
        {
        	$path .= '/';
            $this->original = $file;
            $this->regexp = $this->__constructRegExp($file);
            $this->path   = $path;
            $this->priority = empty($priority) ? L_ALL: $priority;
            $this->maxfiles = empty($maxfiles) ? 1    : $maxfiles;
            $this->maxsize  = empty($maxsize)  ? 1000 : $maxsize;

            if (!file_exists($path))
            {
            	Error::DirNotFound($path);
            }
            $dir  = opendir($path);
            $last = 0;
            $first = time();
            $this->cant = 0;
            $files = array();
            while ($file = readdir($dir))
            {
            	if (preg_match_all($this->regexp, $file, $match, PREG_SET_ORDER))
                {
                	$this->cant++;
	                if (intval($match[0][1]) > $last)
	                {
	                    $last = intval($match[0][1]);
	                }
                    $files[] = $path . $file;
                }
            }
            sort($files);
            $this->files = $files;
            $this->index = $last;
            $this->file  = $this->__getFile($this->__getIndex());
            $this->URLfile  = $this->__getURLFile($this->__getIndex());
            closedir($dir);

			Session::set('BarakusConfig.log_file', $this->file);
			Session::set('BarakusConfig.log_URLfile', $this->URLfile);
        }

    	function logMessage($msg, $type)
        {
           	$this->__log2File($this->file, $msg, $type);
        }

        function __log2File(&$file, $msg, $type)
        {
        	$TYPE = $this->getTypeName($type);
            $date = date(LOG_DATE_FORMAT);
            $MSG  = $date;

            if ($this->config['show-type'])
            {
                $MSG .= ' [' . $TYPE . ']';
            }
            
            $MSG .= ' ' . $msg;

            if ($this->config['show-request-info'])
            {
                $MSG .= " (".Server::get("REMOTE_ADDR")." Request(".$_SERVER['REQUEST_METHOD']."): http://".Server::get('SERVER_NAME').Server::get('REQUEST_URI').")";
            }
            
            $MSG .= "\r\n";

            if (file_exists($file) && filesize($file) + strlen($MSG) >= 1024 * $this->maxsize)
            {
            	if ($this->cant >= $this->maxfiles)
                {
                	if (file_exists($this->files[0]))
                    {
	                    unlink($this->files[0]);
	                    //$this->__rename(0);
	                    //$this->index--;
                    }
                    array_shift($this->files);
                } else {
                	$this->cant++;
                }
            	$this->index++;
            	$file = $this->__getFile($this->__getIndex());
                $this->files[] = $file;
            }
            $fp = fopen($file, "a+");
            fwrite($fp, $MSG);
            fclose($fp);
        }

        function __getFile($number)
        {
        	return $this->path . str_replace($this->type, $number, $this->original);
        }

        function __getURLFile($number)
        {
        	return Barakus::getApplicationPath($this->path()) . '/' . str_replace($this->type, $number, $this->original);
        }

        function __rename($number)
        {
        	if ($number < $this->maxfiles - 1)
            {
            	if (file_exists($this->__getFile($number + 1)))
                {
	                rename($this->__getFile($number + 1), $this->__getFile($number));
	                $this->__rename($number + 1);
                } else {
                	$this->index = $number;
                    $this->cant  = $number - 1;
                }
            }
        }

        function __getIndex()
        {
        	return str_repeat('0', $this->maxdigits - strlen(strval($this->index))) . $this->index;
        }

        function __constructRegExp($file)
        {
        	preg_match('|\%[dDt]|U', $file, $match);
            switch ($match[0])
            {
            	//case '%t':
            	case '%d': $replace = '[0-9]*'; break;
                //case '%D': $replace = '[0-9]{4}\-[0-9]{2}\-[0-9]{2}'; break;
                default: $replace = '';
            }
            $this->type = $match[0];
        	return '|' . str_replace($this->type, "($replace)", str_replace(array('.', '-'), array('\.', '\-'), $file)) . '|U';
        }

		function path()
		{
			return 'Application.Logs';
		}

		function getFileDefault()
		{
			return 'error-%d.log';
		}

	}

?>