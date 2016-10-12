<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2006
 */

Barakus::import("Barakus.Libs.DomitXML.xml_domit_lite_parser");
class ConfigGenerator
{
	var $xmldoc;
	var $Config = array();
	var $file;
	var $HtaccessBuffer = '';

	function ConfigGenerator()
	{
		$this->Config = get_object_vars(new BarakusConfig);
		$this->file = ConfigGenerator::file();
		$this->parse();
	}
    function getCache()
    {
        $config_file_cache = ConfigGenerator::getCacheFile();
        if (file_exists($config_file_cache))
        {
            $config_serialized_array = file_get_contents($config_file_cache);
            $BarakusConfig           = unserialize($config_serialized_array);

            return $BarakusConfig;
        }
        return array();
    }
	function cacheUpdated()
	{
        $BarakusConfig = ConfigGenerator::getCache();
        clearstatcache();
		return (filemtime(ConfigGenerator::file()) == $BarakusConfig['BarakusChangeConfigFileTime']);
	}
	function getValidConfig()
	{
        
		$BarakusConfig = ConfigGenerator::getCache();
        clearstatcache();
		if (!is_array($BarakusConfig) || (filemtime(ConfigGenerator::file()) != $BarakusConfig['BarakusChangeConfigFileTime']))
		{
			if (!ConfigGenerator::cacheUpdated())
			{
				$ConfigGenerator = new ConfigGenerator();
				$ConfigGenerator->save();
			}
		}

		if ($BarakusConfig['custom_htaccess'] && $BarakusConfig['custom_htaccess_filetime'] < filemtime($BarakusConfig['custom_htaccess_file']))
		{
			ConfigGenerator::generateHtaccess($BarakusConfig);
		}

		return $BarakusConfig;
	}
	function file()
	{
		return CONFIGURATION_FILE;
	}
	function parse()
	{
		$parseSAXY     = true;
		$this->xmldoc  = new DOMIT_Lite_Document();
		$success       = $this->xmldoc->loadXML($this->file, $parseSAXY);
		$this->arreglo = $this->xmldoc->toArray();
	}
	function save()
	{
		$config = $this->arreglo['#document'][0]['configuration'];

		foreach($config as $elem)
		{
			foreach($elem as $key => $valor)
			{
				$func = str_replace(" ", "", ucwords(str_replace("-", " ", $key)));
				if (!method_exists($this, $func))
				{
					die("TODO: Error method dont exists " . $func);
				}
				$this->$func($elem[$key], $elem[$key]['attributes']);
			}
		}

		$this->Config['BarakusChangeConfigFileTime'] = filemtime(ConfigGenerator::file());

		$this->__save($this->Config);
		$this->__store_config_cache();
		$this->generateHtaccess($this->Config);
            
		return $this->Config;
	}
	function __store_config_cache()
	{
		$fp = fopen($this->getCacheFile(), "w+");
		$serialized_array = serialize($this->Config);
		fwrite($fp, $serialized_array, strlen($serialized_array));
		fclose($fp);
	}
	function getCacheFile()
	{
		return Barakus::getPath('Application.Temp.Cache') . '/__'.APP_NAME.'__config.cache';
	}
	function __save($config)
	{
		//Session::set('BarakusConfig',               $config);
//		Session::set('BarakusChangeConfigFileTime', filemtime(ConfigGenerator::file()));
	}
    
	function ViewsDir($elementos, $atributos)
	{
		$this->Config['views_dir']      = Barakus::getPath($atributos['url']);
		$this->Config['views_dir_real'] = $atributos['url'];
	}
	function ViewsCompileDir($elementos, $atributos)
	{
		$this->Config['views_compile_dir'] = Barakus::getPath($atributos['url']);
		$this->Config['views_compile_dir_real'] = $atributos['url'];
	}
	function ViewsConfigDir($elementos, $atributos)
	{
		$this->Config['views_config_dir'] = Barakus::getPath($atributos['url']);
		$this->Config['views_config_dir_real'] = $atributos['url'];
	}
    function ControllerDir($elementos, $atributos)
    {
        $this->Config['controller_dir'] = Barakus::getPath($atributos['url']);
        $this->Config['controller_dir_real'] = $atributos['url'];
    }
    function ControllerDirs($elementos, $atributos)
    {
        foreach($elementos as $elemento)
        {                                                                                                 
            $url = $elemento['controller-dir']['attributes']['url'];
            if (!empty($url))
            {
                $this->Config['controller_dirs'][] = array('real-dir' => Barakus::getPath($url), 'dir' => $url);
            }
        }
    }
    function PluginsDir($elementos, $atributos)
    {
        $this->Config['plugins_dir'] = Barakus::getPath($atributos['url']);
        $this->Config['plugins_dir_real'] = $atributos['url'];
    }
    function ModelDir($elementos, $atributos)
    {
        $this->Config['model_dir'] = Barakus::getPath($atributos['url']);
        $this->Config['model_dir_real'] = $atributos['url'];
    }
    function ModelDirs($elementos, $atributos)
    {
        foreach($elementos as $elemento)
        {                                                                                                 
            $url = $elemento['model-dir']['attributes']['url'];
            if (!empty($url))
            {
                $this->Config['model_dirs'][] = array('real-dir' => Barakus::getPath($url), 'dir' => $url);
            }
        }
    }
	function Cache($elementos, $atributos)
	{
		$this->Config['cache_dir'] = Barakus::getPath($atributos['url']);
		$this->Config['cache_dir_real'] = $atributos['url'];
		$this->Config['cache']     = $atributos['enabled'];
	}
	function DataBases($elementos, $atributos)
	{
		$total = count($elementos) - 1;
		$elemens = array();

		for ($i = 0; $i < $total; $i++)
		{
			$data_base = array();
			foreach($elementos[$i]['data-base'] as $elemento)
			{
				$data_base = array_merge($data_base, $elemento);
			}

			$var_name  = "db_";
			if (!empty($data_base['name']))
			{
				$var_name .= $data_base['name']."_";
			}
			$var_name_server = $var_name . "server";
			$var_name_user   = $var_name . "user";
			$var_name_pass   = $var_name . "pass";
			$var_name_db     = $var_name . "dbname";
            $var_name_driver = $var_name . "driver";
            $var_name_debug = $var_name . "debug";

            $this->Config[$var_name_debug]      = $data_base['debug'] === "true";
            $this->Config[$var_name_server]     = $data_base['server'][0];
			$this->Config[$var_name_user]       = $data_base['user'][0];
			$this->Config[$var_name_pass]       = $data_base['password'][0];
			$this->Config[$var_name_db]         = $data_base['dbname'][0];
			$this->Config[$var_name_driver]     = $data_base['driver'][0];
            $this->Config['db_cache']           = $data_base['cache'] === "true";
            $this->Config['DBManager']          = $atributos['manager'];
			$this->Config['DBClassesPath']      = Barakus::getPath($data_base['classes']['attributes']['path']);
			$this->Config['DBClassesPath_real'] = $data_base['classes']['attributes']['path'];
            
            if (!empty($data_base['attributes']['default']) && $data_base['attributes']['default'] == 'true')
            {
                $this->Config['default_db']     = $data_base['dbname'][0];
            }
		}
        if (empty($this->Config['default_db']))
        {
            $this->Config['default_db']     = $data_base['dbname'][0];
        }
	}
	function Application($elementos, $atributos)
	{
		$this->Config['application_mode'] = $atributos['mode'];
		$this->Config['log_errors']       = empty($atributos['log-errors']) ? 'false' : $atributos['log-errors'];
        $this->Config['log_type']         = empty($atributos['log-type']) ?   'file'  : $atributos['log-type'];
        $this->Config['on_error_controller'] = $atributos['on-error'];

		$app = array();
		foreach($elementos as $elemento)
		{
			$app = array_merge($app, $elemento);
		}

		$this->Config['DefaultPage']   = empty($app['default-page']['attributes']['page'])   ? 'Home' : $app['default-page']['attributes']['page'];
		$this->Config['DefaultAction'] = empty($app['default-page']['attributes']['action']) ? '' :     $app['default-page']['attributes']['action'];


		$this->Config['debug_error_page']  = $this->__setErrorPage('debug-error-page', $app);
        $this->Config['online_error_page'] = $this->__setErrorPage('online-error-page', $app);
        
        $this->Config['internal_debug_error_page'] = $app['debug-error-page'][0];
        $this->Config['internal_online_error_page'] = $app['online-error-page'][0];
		//$this->Config['custom_fatal_error_page'] = $app['custom-fatal-error-page'][0];
		//$this->Config['custom_error_page']       = $app['custom-error-page'][0];

		$this->Pages($app['pages'], $app['pages']['attributes']);
        $this->Services($app['services'], $app['services']['attributes']);
		if (count($app['language']) > 0)
		{
			$this->Language($app['language'], $app['language']['attributes']);
		}
	}
    
	function Language($element, $attributes)
	{
		$this->Config['language_default'] = $attributes['default'];
		$this->Config['language_path']    = !empty($attributes['path']) ? Barakus::getPath($attributes['path']) : '';
		$this->Config['language_method'] = $attributes['method'];
		$this->Config['language_var']        = $attributes['var'];
        
        $expl = explode(',', $attributes['availables']);
        if (count($expl) > 0)
        {
            foreach ($expl as $lang)
            {
                $this->Config['language_availables'][] = trim($lang);
            }
        }

		if (!in_array($this->Config['language_method'], array('cookie', 'session')))
		{
			die("TODO: Error, saveMethod Language not defined or invalid: " . $this->Config['language_method']);
		}
	}
	function __setErrorPage($key, $app)   
	{
		if (!empty($app[$key][0]))
		{
            $file = Barakus::getPath($app[$key][0]) . "." . $this->Config["view_extension"];
			if (!file_exists($file))
			{
				Error::FileNotFound($file);
			} else {
				return $file;
			}
		}
		return '';
	}
	function Locale($elementos, $atributos)
	{
		if (eregi(',', $atributos['lang']))
		{
			$atributos['lang'] = explode(',', $atributos['lang']);
			foreach($atributos['lang'] as $i => $v)
			{
				$atributos['lang'][$i] = trim($atributos['lang'][$i]);
			}
		}
		$this->Config['locale_lang'] = $atributos['lang'];
	}
	function Pages($elementos, $atributos)
	{
		$this->Config['pages_theme'] = $atributos['theme'];

		$pages = array();
		if (count($elementos) == 0)
		{
			return;
		}
		//************ SI NO EXISTE PAGE QUE TIRE EXCEPTION
		foreach($elementos as $elemento)
		{
			$pages[$elemento['page']['attributes']['page']]['theme'] = $elemento['page']['attributes']['theme'];
		}
		$this->Config['pages'] = $pages;
	}
    function Services($elementos, $atributos)
    {
    	if (!is_array($elementos))
    	{
    		return;
    	}
    	array_shift($elementos);
        foreach ($elementos as $elemento)
        {
        	$atr = $elemento['service']['attributes'];
        	unset($elemento['service']['attributes']);
        	switch($atr['name'])
        	{
        		case 'log':             $this->ServiceLog($elemento['service'], $atr); break;
        		case 'custom-htaccess': $this->ServiceCustomHtaccess($elemento['service'], $atr); break;
        		case 'tiny-url':        $this->ServiceTinyUrl($elemento['service'], $atr); break;
        		default: die("TODO: ERROR no existe nombre de servicio: " . $atr['name']);
			}
        }
    }

    function ServiceCustomHtaccess($elementos, $atr)
    {
		$this->Config['custom_htaccess']      = false;
		if (empty($atr['file']))
		{
			die("TODO: ERROR servicio custom-htaccess sin setear parametro 'file'");
		}
		if (empty($atr['path']))
		{
			die("TODO: ERROR servicio custom-htaccess sin setear parametro 'path'");
		}

		$file = Barakus::getPath($atr['path']) . '/' . $atr['file'];
		if (file_exists($file))
		{
			$this->Config['custom_htaccess']          = true;
			$this->Config['custom_htaccess_file']     = $file;
		} else {
			Error::FileNotFound($file);
		}
	}

    function ServiceTinyUrl($elementos, $atributos)
    {
    	if (!is_array($elementos))
    	{
    		return;
    	}
		$params = array();
		foreach($elementos as $elemento)
		{
			if (isset($elemento['global-patterns']))
			{
		    	$globals_patterns = $elemento['global-patterns']['attributes'];
			} else {
				$params[] = $elemento['param'];
			}
		}

		if (!is_array($params))
		{
			return;
		}
		foreach($params as $param)
		{
			$atrs     = $param['attributes'];
			$rule     = $this->getTinyURLRule($atrs, $globals_patterns);
			$rules[0][]  = $rule;
			$rules[md5($rule['vars'])] = $rule;
		}
		$this->Config['tiny_url_rules'] = $rules;
		$this->Config['tiny_url']       = true;
	}

	function generateHtaccess(&$config)
	{
		if ($config['custom_htaccess'])
		{
			$echo    .= file_get_contents($config['custom_htaccess_file']);
			$filetime = filemtime($config['custom_htaccess_file']);
			Session::set("BarakusConfig.custom_htaccess_filetime", $filetime);
			$config['custom_htaccess_filetime']                  = $filetime;
		}
		if ($config['tiny_url'])
		{
			$rules = $config['tiny_url_rules'][0];
			$echo .= "
#*** BARAKUS AUTO GENERATED mod_rewrite CODE ***
RewriteEngine on
RewriteBase ".DOCUMENT_APP."/\n";
			foreach($rules as $i => $rule)
			{
				$action = empty($rule['action']) ? '' : '&action=' . $rule['action'];
				$page   = $rule['page'];
				$not_mod_rewrite   = $rule['vars'];
				
				$pattern = $rule['pattern'];
				$echo .= "RewriteRule ^$pattern$ ".$not_mod_rewrite."\n";
				$config['tiny_url_rules'][0][$i]['not_mod_rewrite'] = $not_mod_rewrite;
			}
		}

		if (!empty($echo))
		{
			if (!is_writeable(Barakus::getPath("") . '.htaccess'))
			{
				//die("TODO: Error .htaccess no writeable " . Barakus::getPath("") . '.htaccess');
			}
			$fp = fopen(Barakus::getPath("") . '/.htaccess', "w+");
			fwrite($fp, $echo, strlen($echo));
			fclose($fp);
			//Controller::redirect(Server::get('SCRIPT_NAME') . '?' . Server::get('QUERY_STRING'));
		}
	}

	function getTinyURLRule($atrs, $globals_patterns)
	{
		$ret  = array();

		$ret['pattern'] = $atrs['pattern'];
		$ret['page']    = $atrs['page'];
		$ret['action']  = $atrs['action'];
		$ret['original_pattern'] = DOCUMENT_APP . '/' . $atrs['pattern'];

		if (!empty($ret['action']))
		{
			$ret['params'][] = $ret['action'];
		}

		unset($atrs['pattern'], $atrs['page'], $atrs['action']);

		$vars = '';

		preg_match_all('|\{(.*)\}|U', $ret['pattern'], $URLpositions);
		$URLvars = array();
		foreach($URLpositions[1] as $i => $value)
		{
			$URLvars[$value] = $i+1;
		}
		foreach($atrs as $name => $value)
		{
			if (preg_match('|global\.(.*)$|U', $value, $match))
			{
				$value = $globals_patterns[$match[1]];
				if (empty($value))
				{
					die("TODO: ERROR global-pattern no definido para: " . $name ." - " . $match[1]);
				}
			}

			$ret['pattern'] = str_replace('{'.$name.'}', !empty($value) ? '('.$value.')' : '', $ret['pattern']);
			$vars .= '&' . $name . '=' . (!empty($URLvars[$name]) ? '$' . $URLvars[$name] : $value);
			$ret['params'][] = $name;
		}
		$ret['vars']     = basename(Server::get('PHP_SELF')) . "?page=" . $ret['page'] . (empty($ret['action']) ? '' : '&action=' . $ret['action']) . $vars;
		//$ret['params'][] = $ret['page'];

		return $ret;
	}

	function ServiceLog($elementos, $atr)
	{
		if (empty($atr['type']))
		{
			die("TODO: ERROR servicio log sin setear parametro 'type'");
		}
		if (empty($atr['priority']))
		{
			die("TODO: ERROR servicio log sin setear parametro 'priority'");
		}
		if (empty($atr['path']))
		{
			die("TODO: ERROR servicio log sin setear parametro 'path'");
		}
    	$atr['priority'] = Logger::getPriority($atr['priority']);
    	if (!file_exists(Barakus::getPath($atr['path'])))
    	{
    		if ($atr['create-ifnotexists'] == "true")
    		{
    			if (!mkdir(Barakus::getPath($atr['path']), 0775))
    			{
    				Error::CantCreateDir(Barakus::getPath($atr['path']));
				}
    		} else {
				Error::DirNotFound($atr['path']);
			}
    	}
        
        $atr['show-type']         = ($atr['show-type'] == 'true' || empty($atr['show-type']));
        $atr['show-request-info'] = ($atr['show-request-info'] == 'true' || empty($atr['show-request-info']));
        
    	$this->Config['log_config'][] = $atr;
	}

    function Includes($elementos, $atributos)
    {
    	if (!is_array($elementos))
    	{
    		return;
    	}
    	$total = count($elementos)-1;
    	for ($i = 0; $i < $total; $i++)
		{
			$include = $elementos[$i]['include']['attributes']['path'];
            $this->Config['includes'][] = $include;
            Barakus::import($include);    
    	}
	}
}
?>