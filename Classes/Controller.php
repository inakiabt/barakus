<?php

/**
 * Clase Controller
 *
 * @version $Id$
 * @copyright 2006
 */
Barakus::import("Barakus.Helpers.ArrayHelper");
class Controller
{
	var $view         = null;
	var $lang_manager = null;
	var $current_lang = null;
	var $IsPost       = false;
	var $browser      = null;
    var $validation   = null;
    var $validation_auto = false;
    var $validated       = true;
    var $validating      = false;
    var $isAJAXCallBack  = false;
    var $resetValidationOnOneTry = false;
    var $loaded                  = false;

	function preLoad($className)
	{
		$this->Name = str_replace('.', '_', $className);
	}
	function onLoad()
	{               
        $this->loaded = true;

		if (($saveMethod = Config::get('language_method')))
		{
			$this->view = new InternationalView();

			$classSaveMethod = ucfirst(strtolower($saveMethod)) . 'LanguageHelper';
            
            Barakus::import('Barakus.Helpers.'.$classSaveMethod);
            
			$this->lang_manager = new $classSaveMethod;
            $this->lang_manager->setDefault(Config::get('language_default'));
            $this->lang_manager->setVar(Config::get('language_var'));
            $this->lang_manager->setAvailables(Config::get('language_availables'));
            $this->lang_manager->setPath(Config::get('language_path'));

			$this->current_lang = $this->lang_manager->getLang();

            $this->view->lang_path = $this->lang_manager->path;
			$this->view->setLanguage($this->current_lang);
		} elseif (Config::get('cache') == 'clip') {
			$this->view = new ClipCacheView();
            $this->view->register_clipcache();
            $this->view->cache_lifetime = 20;
        } else {
            $this->view = new View();
        }
        
        $this->_setValidation();

		if (Request::isPost())
		{
            $this->IsPost = true;
            $this->saveState();
                        
            if (!$this->isAJAXCallBack && $this->validate_auto)
            {            
                if (!$this->validate(Post::toArray()))
                {
                    $this->redirectReferer();
                }
            }
		}
        
        $this->view->setController($this);     
                                     
        $this->view(Barakus::getPage() . '.' . (Request::get('action') != '' ? strtolower(Request::get('action')) : 'index'));
        
		//$this->browser = $this->getBrowser();
	}
    function _setValidation()
    {
        $ValidationResult = Session::get('Validation.' . $this->validationName() . '.ValidationResult');
        if ($ValidationResult == 'failed')
        {
            $this->view->assign('ValidationFailed', 1);
            $this->validated = false;
        }
    }
    function getName()
    {
        return $this->Name;
    }
	function init()
	{
	}
	function end()
	{                       
        $this->view->end();       
        $this->view->show();
                                 
        $ValidationResult = Session::get('Validation.' . $this->validationName() . '.ValidationResult');
        if ($this->resetValidationOnOneTry && Server::get('REQUEST_METHOD') == 'GET' && $ValidationResult == 'failed')
        {
            Session::set('Validation.' . $this->validationName(), array());
        }
	}
	function view($view = '')
	{
		$this->view->compile_check = true;
		$this->view->debugging     = false;

		$this->view->load($view);
        $this->view->assign('URL_BASE', DOCUMENT_APP);
	}

	function model($model = '')
	{
        if (!is_object($this->$model))
        {
            if (eregi(',', $model))
            {
                $models = explode(',', $model);
                foreach ($models as $model)
                {
                    $model = trim($model);
                    $this->$model = Model::getModel($model);
                }
            } else {
                $this->$model = Model::getModel($model);
            }
        }
	}
	function getModel($model = '')
	{
		if (empty($model))
		{
			Error::ModelEmpty();
		}

		Barakus::import(Config::get("model_dir_real") . "." . $model);
		$class = $model.'Model';
		return new $class;
	}
    function setIsAJAXCallBack($is)
    {
        $this->isAJAXCallBack = $is;
    }
    function page()
    {
        return str_replace('.', '_', Barakus::getPage());
    }
    function action()
    {
        return str_replace('.', '_', Barakus::getAction());
    }
	function saveState($state = null, $post = true)
	{
        $url = Server::get('QUERY_STRING');
        parse_str($url);
        $page = $this->validationName(Barakus::getPage(), Request::getSenderAction());
		if ($state == null)
		{
            if ($post)
            {
                $state = Post::toArray();
                if (Files::hasFiles())
                {
                    $state = array_merge_recursive($state, Files::toArray());
                }
            } else {
                $state = Request::toArray();
            }
		}
        Session::set('Session' . $page . 'State', $state);
	}
    function validationName($page = '', $action = -1)
    {
        if (empty($page))
        {
            $page = $this->page();
        }
        
        if ($action === -1)
        {
            $action = Request::getSenderAction(); //$this->action();
        }
        
        return str_replace('.', '_', $page . '_' . $action);
    }
	function restoreState($name = 'state', $page = '', $action = '')
	{                                          
        if (empty($page))
        {
            $page = $this->page();
        }
        
        if (empty($action))
        {
            $action = Request::getSenderAction();//$this->action();
        }
        
        $this->STATE = Session::get('Session' . $this->validationName(Barakus::getPage(), Request::getSenderAction()) . 'State');
        //$this->STATE['validation'] = Session::get('Validation.' . $this->validationName($page, $action));
        $this->STATE['validation'] = Session::get('Validation.' . $this->validationName($page, $action));
        $this->view->assign($name, $this->STATE);
	}
	function resetState()
	{
		Session::set('Session' . $this->validationName(Barakus::getPage(), Request::getSenderAction()) . 'State', '');
        Session::set('Validation.' . $this->validationName($page, $action), array());
	}
	function redirect($location)
	{
		if (empty($location))
		{
			Error::LocationEmpty();
		}
        if (!preg_match('|^http://|i', $location))
        {
            if (Config::get('tiny_url'))
            {
                $location = URLMap::getURL($location);
            } else {
                $location = DOCUMENT_APP . ($location[0] == '/' ? $location : '/' . $location);
            }
        }
		header('Location: ' . $location);
		exit();
	}

	function redirectReferer()
	{
		$this->redirect(Request::Sender());
	}

	function setLanguage($lang)
	{
		if (!empty($lang))
		{
			$this->lang_manager->setLang($lang);
			$this->current_lang = $this->lang_manager->getLang();

			$this->view->setLanguage($lang);

		} else {
			die("TODO: Lang empty");
		}
	}

	function getBrowser()
	{
		if ($this->browser == null)
		{
			LibManager::import('Browscap');
			$this->browser = new Browscap(Config::get('cache_dir'));
		}
		return $this->browser->getBrowser();
	}
    
    function _getFieldValue($array, $field)
    {
        if (ereg('\.', $field))
        {
            $expl = explode(".", $field);
            $value = $array;
            foreach ($expl as $index)
            {
                $value = $value[$index];
            }
            return $value;
        }
            
        return $array[$field];
    }
    
    function validatingReferer()
    {
        return count(Session::get('Validation.' . $this->validationName(Barakus::getPage(), Request::getSenderAction())));
    }
    
    function validating()
    {                          
        return count(Session::get('Validation.' . $this->validationName()));
    }
    
    function validated()
    {
        return $this->validated;
    }
    
    function validateField($field, $data, $rules, $fields = null)
    {
        $valid = true;
        if ($fields == null)
        {
            $fields = new ArrayHelper();
        }
        if (is_array($data))
        {
            $data   = new ArrayHelper($data);
        }
        $fields->set($field.'.data', $data->get($field));
        if (is_array($rules))
        {              
            if (isset($rules['rule']))
            {          
                if ($rules['ignoreIfPrevError'])
                {
                    continue;
                }
                $fields->set($field, $this->validate_field($fields->get($field), $rules));
                if (!$fields->get($field.'.valid'))
                {
                    $valid = false;
                }
            } else {
                foreach ($rules as $i => $rule)
                {
                    if ($rule['ignoreIfPrevError'])
                    {
                        continue;
                    }
                    $fields->set($field, $this->validate_field($fields->get($field), $rule, $i));
                    if (!$fields->get($field.'.valid'))
                    {
                        $valid = false;
                    }
                }
            }
        } else {
            $fields->set($field, $this->validate_field($fields->get($field), $rules));
            if (!$fields->get($field.'.valid'))
            {
                $valid = false;
            }
        }
        $this->validated = $valid;
        
        return $fields;
    }
    
    function validate($data, $_rules = null)
    {
        if (method_exists($this, 'prevalidation'))
        {
            $this->prevalidation();
        }
        if ($_rules != null)
        {
            $this->validation = $_rules;    
        }
        
        $valid = true;
        $fields = new ArrayHelper();       
        $data   = new ArrayHelper($data);
        $files  = null;
        
        if (Files::hasFiles())
        {
            $files = new ArrayHelper(Files::toArray());
        }
        
        foreach ($this->validation as $field => $rules)
        {
            if (!is_null($files))
            {
                $file = $files->get($field);
                $count = count($file);
                if ($count)
                {
                    $data->set($field, $file);
                    $data->set($field.'.__ISFILE__', true); 
                }
            }                  
            $fields = $this->validateField($field, $data, $rules, $fields);
        }

        $this->validated = true;
        foreach ($fields->toArray() as $field)
        {
            if ($field['valid'] != 1)
            {
                $this->validated = false;
                break;
            }
        }
        
        $this->setValidationResult($fields);
        
        return $this->validated;
    }
    
    function setValidationResult($fields)
    {
        $fields->set('ValidationResult', ($this->validated ? 'pass' : 'failed'));
        
        Session::set('Validation.' . $this->validationName(Barakus::getPage(), Request::getSenderAction()), $fields->toArray());
    }
    
    function validateOneField($field, $data, $rules)
    {
        $this->validated = true;
        $fields = $this->validateField($field, $data, $rules);
        $this->setValidationResult($fields);
        
        return $this->validated;
    }
    
    function validate_rule($datos, $RULE, $index = 0)
    {
        $validation = false;
        $data['data'] = $datos;
        if (is_string($datos))
        {
            $data['data'] = trim($datos);
        }
        switch($RULE)
        {
            case 'alpha':    $validation = intval(ctype_alpha($data['data'])); break;
            case 'alphaNum': $validation = intval(ctype_alnum($data['data'])); break;
            case 'empty':    $validation = intval(empty($data['data'])); break;
            case 'notEmpty': $validation = intval(!empty($data['data'])); break;
            case 'numeric':  $validation = intval(ctype_digit($data['data'])); break;
            case 'email':    $validation = preg_match('/\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b/i', $data['data']); break;
            default:
                switch ($RULE[0])
                {
                    case 'between':   $validation = (intval($data['data']) >= $RULE[1] && intval($data['data']) <= $RULE[2]); break;
                    case 'custom':    
                        $method = 'validation_' . $RULE[1];
                        if (!method_exists($this, $method))
                        {
                            Error::CustomValidationInvalid($method);
                        }
                        $preValidationMethod = 'pre' . $method;
                        if (method_exists($this, $preValidationMethod))
                        {
                            @$this->{$preValidationMethod}($data['data'], $index);
                        }
                        
                        $validation = intval(@$this->$method($data['data'], $index)); 
                        
                        $postValidationMethod = 'post' . $method;
                        if (method_exists($this, $postValidationMethod))
                        {
                            $dataObj = new ArrayHelper($data);
                            $postMessage = @$this->{$postValidationMethod}($validation, $dataObj, $index);
                            $data = $dataObj->toArray();
                        }
                        break;
                    case 'regex': $validation = preg_match('@'.str_replace('@', '\@', $RULE[1]).'@', $data['data']); break;
                    case 'minLength': $validation = (strlen($data['data']) >= $RULE[1]); break;
                    case 'maxLength': $validation = (strlen($data['data']) <= $RULE[1]); break;
                }
        }
        $data['valid'] = $validation;
        return $data;
    }
    
    function validate_field($data, $rule)
    {           
        if (!isset($data['valid']))
        {
            $data['valid'] = 1;
        }
        if (!$data['valid'])
        {
            return $data;
        }
            
        if (is_array($rule))
        {
            $RULE = $rule['rule'];
            $message = $rule['message'];
        } else {
            $RULE = $rule;
            $message = 'error';
        }
                  
        $validation = 1;
        if (empty($data['data']) && $rule['required'] === false) // porque habia puesto true??
        {
            $validation = 1;
        } else {
            if (is_array($data['data']) && !$data['data']['__ISFILE__'])
            {
                foreach ($data['data'] as $i => $value)
                {
                    $data[$i] = $this->validate_rule($value, $RULE, $i);
                    if (empty($data[$i]['message']))
                    {
                        $data[$i]['message'] = $message;
                    }
                    if (!$data[$i]['valid'])
                    {
                        $validation = false;
                    }
                }
            } else {
                $data = $this->validate_rule($data['data'], $RULE);
                if (!$data['valid'])
                {
                    $validation = false;
                }
            }
        }
        
        if (!$validation)
        {
            if (empty($message))
            {
                $message = 'Error';
            }
            $data['message'] = $message;
        }
        $data['valid'] = $validation;
        return $data;
    }
}


?>