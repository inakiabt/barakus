<?php

class Language
{
	var $prefix = 'LANGUAGE_';
	var $language_var = '';

	function Language($lang = false)
	{
		$this->setLang($lang);
	}

	function setLang($lang = false)
	{
		$this->language_var = Config::get('language_var');
		if (empty($this->language_var))
		{
			die("TODO: Error, variable de idioma vacia"); //TODO
		}
		if (!$lang)
		{
			$lang = Request::get($this->language_var);
		} elseif ($this->getLang() !== false && empty($lang)) {
			$lang = Config::get('language_default');
		}

		if (!empty($lang))
		{
			$this->__setLang($lang);
		}
	}

	function getLang()
	{
		die("Not implemented"); //TODO
	}

	function __setLang()
	{
		die("Not implemented"); //TODO
	}
}


class CookieLanguage extends Language
{
	function __setLang($lang)
	{
		if (!setcookie($this->prefix . $this->language_var, $lang, time()+3600))
		{
			die("TODO: no se pudo setear cookie");
		}
		$_COOKIE[$this->prefix . $this->language_var] = $lang;
	}

	function getLang()
	{
		return $_COOKIE[$this->prefix . $this->language_var];
	}
}

class SessionLanguage extends Language
{
	function __setLang($lang)
	{
		$_SESSION[$this->prefix . $this->language_var] = $lang;
	}

	function getLang()
	{
		return $_SESSION[$this->prefix . $this->language_var];
	}
}
?>