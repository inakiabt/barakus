<?php
    Barakus::import('Barakus.Helpers.LanguageHelper');
    class CookieLanguageHelper extends LanguageHelper
    {
        function _getCurrentLanguage()
        {
            $cookie = $_COOKIE['Barakus.lang'];
            return (empty($cookie) ? $this->default : $cookie);
        }
        function _save($lang)
        {
            $_COOKIE['Barakus.lang'] = $lang;
        }
    }
?>
