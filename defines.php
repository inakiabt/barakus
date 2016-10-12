<?php
    if (!defined('APP_NAME'))   
    {
        define('APP_NAME', 'web');
    }
    
    if(!defined('DOCUMENT_ROOT'))
    {
        define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT']);
    }
    
    if(!defined('APP_PATH'))
    {
        define('APP_PATH',       dirname(DOCUMENT_ROOT . $_SERVER['PHP_SELF']));
    }
    
    if(!defined('FRAMEWORK_PATH'))
    {
        define('FRAMEWORK_PATH', dirname(__FILE__));
    }

    if (!defined('DOCUMENT_APP'))
    {
        $expl = explode('/', $_SERVER['PHP_SELF']);
        array_pop($expl);
        define('DOCUMENT_APP', implode('/', $expl));
    }
    
    if (!defined('WEB_PATH'))
    {
        define('WEB_PATH', DOCUMENT_ROOT . DOCUMENT_APP);
    }
    
    if(!defined('WEB_URL'))
    {
        define('WEB_URL', 'http://' . $_SERVER['HTTP_HOST']);
    }
    
    if(!defined('APP_URL'))
    {
        define('APP_URL',       WEB_URL . DOCUMENT_APP . '/');
    }
    
    if (!defined('E_BARAKUS'))
    {
        define('E_BARAKUS', 4096);
    }

    if (!defined('E_EXCEPTION'))
    {
        define('E_EXCEPTION', 8192);
    }

    if (!defined('E_ERROR_SUPPORTED'))
    {
        if (!defined('E_STRICT'))
        {
            define('E_STRICT', 2048);
        }
        define('E_ERROR_SUPPORTED', (E_STRICT | E_NOTICE));
    }
?>
