<?php

class Backtrace
{
	var $line_init = 15;
	var $line_end  = 15;

	function Debug($type, $msg, $file, $line, $context, $debug = null)
	{
        if ($debug == null)
        {
            $debug = debug_backtrace();
        }
        LogManager::log("[" . Error::getType($type) . "] " . $file . " (line " . $line . "): " . $msg, L_ERROR);

		if ($backtrace = $this->getBacktrace($type, $msg, $file, $line, $debug))
		{
			$this->ShowSource($type, $backtrace['error']['file'], $backtrace['error']['line'], false, $backtrace);
		}
	}
	function removeslashes($str)
	{
		return str_replace("\\\\", '\\', $str);
	}
	function ShowSource($type, $file, $line, $source_only = true, $backtrace = array())
	{
		$tpl = Config::get("debug_error_page");
		if (!empty($tpl))
		{
			$file  = $this->removeslashes(is_array($backtrace['error']) ? $backtrace['error']['file'] : $file);
			$linde = is_array($backtrace['error']) ? $backtrace['error']['line'] : $line;

			$view = new View();
			$view->register_modifier('sslash', 'stripslashes');
			$view->assign('DOCUMENT_APP', DOCUMENT_APP);
			$view->assign('source_only',  $source_only);
			$view->assign('type',         Error::getType($type));
			$view->assign('file',         $file);
			$view->assign('line',         $line);
			$view->assign('error',        $backtrace['error']);
			unset($backtrace['error']);
			$view->assign('lineas',       $this->getFileLines($this->removeslashes($file), $line, $this->line_init, $this->line_end, $context));
			$view->assign('backtrace',    $backtrace);
			$view->show($tpl);
			die();
		}
	}
	function getBacktrace($type, $msg, $file, $line, $debug)
	{
		$backtrace = array();

		if ($debug > 0)
		{
			if ($type == E_USER_ERROR)
			{
				$file = $debug[4]['file'];
				$line = $debug[4]['line'];

				$init = 5;
			} else {
				$init = 3;
			}
			$total = count($debug);
			$backtrace['error'] = array(
								'file' => addslashes($file),
								'file_strip' => $this->removeslashes($file),
								'line' => $line,
								'msg'  => $msg
								);
			$i = 1;
			for ($num = $init; $num < $total; $num++)
			{
				$trace         = $debug[$num];
				$trace['file'] = addslashes($trace['file']);
				$trace['file_strip'] = $this->removeslashes($trace['file']);

				if (!is_array($trace['args']))
				{
					$trace['args'] = '';
				} else {
                    foreach ($trace['args'] as $index => $value)
                    {
                        if (is_string($value))
                        {
                            $value = strip_tags($value);
                        } else {
                            $value = 'Object';
                        }
                            $trace['args'][$index] = substr($value, 0, 150) . (strlen($value) > 150 ? '...' : '');
                    }
					$trace['args'] = implode(', ', $trace['args']);
				}
				$backtrace[$i] = $trace;
				$i++;
			}
		}
		return $backtrace;
	}
	function getFileLines($file, $line, $prev = 25, $next = 25, $context = array())
	{
	    if (!(file_exists($file) && is_file($file)))
		{
	        Error::FileNotFound($file);
	        return false;
	    }
	    //read code
	    ob_start();
	    highlight_file($file);
	    $data = ob_get_contents();
	    ob_end_clean();

	    //seperate lines
	    $data  = explode('<br />', $data);
	    $count = count($data) - 1;

	    //count which lines to display

	    $start = $line - $prev;
	    if ($start < 1) {
	        $start = 1;
	    }
	    $end = $line + $next;
	    if ($end > $count) {
	        $end = $count + 1;
	    }

	    $highlight_default = ini_get('highlight.default');

		$lineas = array();
	    while ($start <= $end)
		{
			$lineas[$start] = $data[$start - 1];
	        ++$start;
	    }

	    return $lineas;
	}
}

?>