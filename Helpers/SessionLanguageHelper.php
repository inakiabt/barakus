<?php
    Barakus::import('Barakus.Helpers.LanguageHelper');
    class SessionLanguageHelper extends LanguageHelper
    {
        function SessionLanguageHelper()
        {
            parent::LanguageHelper();
        }
        function _getCurrentLanguage()
        {
            $session = Session::get('Barakus.lang');
            return (empty($session) ? $this->default : $session);
        }
        function _save($lang)
        {
            Session::set('Barakus.lang', $lang);
        }
    }
?>
