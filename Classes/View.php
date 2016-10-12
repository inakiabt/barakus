<?php

/**
 * View
 *
 * @package views
 * @author Iñaki Abete
 * @copyright Copyright (c) 2006
 * @version $Id$
 * @access public
 **/
Barakus::import('Barakus.Helpers.ViewHelper');
LibManager::import('Smarty.internals.Barakus.smarty_cache_eaccelerator');
class View extends Smarty
{
	//Propiedad que guarda el archivo template a compilar y mostrar
	var $Template;
	var $extension;
    var $layout = false;
    
    var $controller = null;
    
    var $_var_assign = true;
    var $__events    = false;
    var $events      = array();
    
    var $_actual_tpl;
    var $_last_tpl;
    var $_layout_tpl = array();
    var $_layout_pop = true;
    var $showed      = false;
    
    var $model;

	/**
	 * View::View()
	 **/
	function View()
	{
		parent::Smarty();
        
        $this->compile_id  = Config::get('webType');
        
		$this->extension    =  '.' . Config::get('view_extension');

		//Se guardarán las vistas
		$this->template_dir = Config::get('views_dir');

		//Se generarán las compilaciones de las vistas
		$this->compile_dir  = Config::get('views_compile_dir');

		//Se guardarán archivos de configuracion de las vistas
		$this->config_dir   = Config::get('views_config_dir');

		//Se guardará el cache
		$this->cache_dir    = Config::get('cache_dir');
        
        $this->cache_handler_func = 'smarty_cache_eaccelerator';
        
        $this->plugins_dir[] = Barakus::getPath(Config::get('plugins_dir_real') . '.View.Repository');
        //Forzar compilacion si la aplicacion esta en modo debug
        if (Config::get('application_mode') == 'debug' || Config::get('application_mode') == 'debug-low')
        {
            $this->force_compile  = true;

            //$this->debugging      = true;

            //Ej:  http://www.foo.dom/index.php?SMARTY_DEBUG
            //$this->debugging_ctrl = 'URL';
        } else {
            $this->force_compile  = false;
            $this->compile_check  = false;
        }

		//Cache enabling
        switch (Config::get('cache'))
        {
            case 'true': 
                $this->caching = 2; 
                $this->force_compile  = false;
                break;
            case 'clip': 
                $this->caching = 1; 
                $this->force_compile  = false;
                break;
            default: $this->caching = 0;
        }
        
		//Use Subdirs
		$this->use_sub_dirs = true;

		//Path idioma
		$this->lang_path = Config::get('language_path') . '/';

        include_once $this->_get_plugin_filepath('block', 'layout_block');
        
        $this->register_block('layout_block', 'smarty_block_layout_block');
        $this->register_function('control',   'smarty_function_contro');
        
        $this->compiler_class = 'ViewCompiler';
        $this->compiler_file  = Barakus::getPath('Barakus.Classes.ViewCompiler') . '.php';
        
        $this->force_compile_old = $this->force_compile;

	}
    
    function initCache($cache = 2)
    {
        if ($this->caching > 0)
        {
            $this->force_compile_old = $this->force_compile;
            $this->force_compile = false;
            $this->caching       = $cache;
        }
    }
    
    function endCache()
    {
        if ($this->caching > 0)
        {
            $this->force_compile = $this->force_compile_old;
            $this->caching       = 0;
        }
    }
    
    function _get_auto_filename($auto_base, $auto_source = null, $auto_id = null)
    {
        $_compile_dir_sep =  DIRECTORY_SEPARATOR;
        $_return = $auto_base . DIRECTORY_SEPARATOR;

        if(isset($auto_id)) {
            // make auto_id safe for directory names
            $auto_id = str_replace('%7C',$_compile_dir_sep,(urlencode($auto_id)));
            // split into separate directories
            $_return .= $auto_id . $_compile_dir_sep;
        }

                    $this->__is_template = false;
        if(isset($auto_source)) {
            if (Config::get('cache') == 'true')
            {
                if (md5($this->__createTemplateFile($this->Template)) == md5($auto_source))
                {
                    $this->__is_template = true;
                    $pre_auto_source = md5(Request::getUrl());
                    return $_return . $pre_auto_source . '/' .str_replace(array(Config::get('views_dir')."/", dirname(Config::get('debug_error_page'))), array('', ''), $auto_source);
                }
            }
            $_return .= str_replace(array(Config::get('views_dir')."/", dirname(Config::get('debug_error_page'))), array('', ''), $auto_source);
        }

        return $_return;
    }
    
	/**
	 * View::load()
	 * Método para cargar un template especifico
	 * @param String $tpl nombre del archivo template (Ej.: 'index.tpl'),
	 * este archivo deberá estar en el directorio de vistas
	 **/
	function load($tpl)
	{
		$this->Template = $tpl;
	}
    
    function getTemplate()
    {
        return $this->Template;
    }
    
    function setController($controller)
    {
        $this->controller = $controller;
    }
    
    function __createTemplateFile($tpl)
    {
        return Barakus::getPath(Config::get("views_dir_real") . "." . $tpl) . $this->extension;
    }

	/**
	 * View::show()
	 * Muestra por pantalla el template compilado
	 **/
    function show($tpl = '')
    {
        if ($this->showed)
        {
            return;
        }
        
        $this->showed = true;
        
        $this->showEvents();    
        $this->assign_by_ref("this", $this);
        $this->load_filter('output', 'move_to_head');
        $this->load_filter('pre',    'layout');       
        $Barakus = array(
                        'page' => strtolower(Barakus::getPage())
                    );
        $this->assign('Barakus', $Barakus);
                                           
        $this->display(empty($tpl) ? $this->__createTemplateFile($this->Template) : $tpl);  
    }

    function output()
    {
        $this->showEvents();
        $this->assign_by_ref("this", $this);
        $this->load_filter('output', 'move_to_head');
        $this->load_filter('pre',    'layout');
        
        $Barakus = array(
                        'page' => strtolower(Barakus::getPage())
                    );
        $this->assign('Barakus', $Barakus);

        return $this->fetch($this->__createTemplateFile($this->Template));
    }

	function asset($file)
	{
		$page  = Barakus::getPage();
		$pages = Config::get("pages");

		if (empty($pages[$page]['theme']))
		{
			$theme_name = Config::get("pages_theme");
		} else {
			$theme_name = $pages[$page]['theme'];
		}
		$theme = Barakus::getApplicationPath("Themes." . $theme_name);
		return $theme . '/' . $file;
	}
    
    function page($page, $action = '')
    {
        $url = '?page=' . $page;
        if (!empty($action))
        {
            $url .= '&action=' . $action;
        }
        
        return $this->url($url);
    }

	function url($url)
	{	                   
		return URLMap::getURL($url);
	}
    
    function error($field)
    {                     
        $v = Session::get('Validation.' . $this->controller->validationName() . '.' . $field);
        if (!$v['valid'])
        {
            return $v['message'];
        }
        return '';
    }
    
    function __errors($v, $_field = array())
    {      
        $return = array();    
        if (count($v))
        {
            foreach ($v as $field => $values)
            {
                $_field[] = $field;
                if (is_array($values) && !array_key_exists('data', $values) && !array_key_exists('valid', $values))
                {
                    $return = array_merge($return, $this->__errors($values, $_field));
                } elseif (!$values['valid'])
                {
                    $return[] = array(
                                    'message' => $values['message'],
                                    'field'   => implode('.', $_field)
                                    );
                }
                array_pop($_field);
            }
        }
        return $return;
    }      
    
    function errors()
    {                         
        $v = Session::get('Validation.' . $this->controller->validationName());

        return $this->__errors($v);
    }

	function __set($var, $val)
	{
        if ($this->_var_assign)
        {
		    $this->assign($var, $val);
        }
        $this->$var = $val;
	}
    
    function __get($var)
    {
        if ($var == 'model' && $this->model == null)
        {
            $this->_var_assign = false;
            $this->model = new ModelView();
            $this->_var_assign = true;
            pr($this->model);
        }
        return $this->$var;
    }
    
    function assign($var, $value = null)
    {
        if (is_array($var))
        {
            foreach ($var as $key => $value)
            {
                if (!empty($key))
                {
                    $this->$key = $value;
                }
            }
        } else {
            $this->$var = $value;
        }
        parent::assign($var, $value);
    }
    
    function addJs($js)
    {
        $GLOBALS['__js_files'][] = $js;
    }

    function addCss($css)
    {
        $GLOBALS['__css_files'][] = $css;
    }

	function addEvent($id, $event)
	{
        if (!$this->__events)
        {
            $this->addJs('prototype');
            $this->addJs('Barakus');
        }
        $event->id           = str_replace('.', '_', $id);
        $event->htmlid           = $id;
		$this->events[$id][] = $event;
        
        $this->__events = true;
	}

	function showEvents()
	{
	}

	function noCache()
	{
		$this->caching = 0;
	}
    
    function paginate($pag)
    {        
        $this->_var_assign         = false;
        if (!is_object($this->paginate))
        {
            $this->paginate        = new stdClass();
        }
        $this->_var_assign         = true;
        
        $this->paginate->{$pag}    = new PaginateView($this, $pag); 
    }
    
    function getPluginConnectUrl($plugin, $params = array())
    {
        $url_params = '';
        if (count($params))
        {
            foreach ($params as $name => $value)
            {
                $url_params .= '&' . $name . '=' . $value;
            }
        }
        return $this->url('?BARAKUS_PLUGIN=' . $plugin . '&t=' . time() . $url_params);
    }
    
    function end()
    {
    }
    
    function getWebUrl($path)
    {
        return APP_URL . $path;
    }
}

?>