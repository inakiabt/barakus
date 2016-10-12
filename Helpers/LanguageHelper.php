<?php
    class LanguageHelper
    {
        var $default = 'es';
        var $path    = '';
        var $var     = 'lang';
        var $availables = array();
        
        function LanguageHelper()
        {
            $this->path = Barakus::getPath('Application.Temp.lang').'/';
        }
        function setDefault($value)
        {
            if (!empty($value))
            {
                $this->default = $value;
            }
        }
        function setPath($value)
        {
            if (!empty($value))
            {
                $this->path = $value;
            }
        }
        function setVar($value)
        {
            if (!empty($value))
            {
                $this->var = $value;
            }
        }
        function setAvailables($value)
        {
            if (count($value) > 0)
            {
                $this->availables = $value;
            }
        }
        function getLang()
        {
            $request = Request::get($this->var);
            if (empty($request))
            {
                return $this->_returnLang($this->_getCurrentLanguage());
            }
            return $this->_returnLang($request);
        }
        function _getCurrentLanguage()
        {
            die('Abstract Class!!');
        }
        function _save($lang)
        {
            die('Abstract Class!!');
        }
        function _returnLang($lang)
        {
            $lang = strtolower($lang);
            $lang = count($this->availables) > 0 ? (in_array($lang, $this->availables) ? $lang : $this->default) : $this->default;
            $this->_save($lang);
            
            return $lang;
        }
    }
?>
