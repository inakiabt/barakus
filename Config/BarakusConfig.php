<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2006
 */
class BarakusConfig
{
	//Se guardar�n los modelos
	var $model_dir;

    //Se guardar�n los controllers
    var $controller_dir;

    //Se guardar�n los plugins
    var $plugins_dir;

	//Extension de las vistas
	var $view_extension = 'tpl';

	//Se guardar�n las vistas
	var $views_dir;

	//Se generar�n las compilaciones de las vistas
	var $views_compile_dir;

	//Se guardar�n archivos de configuracion de las vistas
	var $views_config_dir;

	//Se guardar� el cache
	var $cache_dir;

	//Activado el cache
	var $cache = false;

	//Pagina default
	var $DefaultPage = 'Home';

	//Modo de aplicacion (debug | online)
	var $application_mode = 'debug';

	//setLocale
	var $locale_lang = 'es_ES';

	function BarakusConfig()
	{
		//Se guardar�n los modelos
		$this->model_dir          = APP_PATH . '/application/model/';

        //Se guardar�n los controllers
        $this->controller_dir     = APP_PATH . '/application/controller/';
        
        //Se guardar�n los controllers
        $this->controller_dir_real     = 'Application.Controller';
        
        $this->plugins_dir        = APP_PATH . '/application/plugins/';

		//Se guardar�n las vistas
		$this->views_dir          = APP_PATH . '/application/views/';

		//Se generar�n las compilaciones de las vistas
		$this->views_compile_dir  = APP_PATH . '/application/temp/view_compile/';

		//Se guardar�n archivos de configuracion de las vistas
		$this->views_config_dir   = APP_PATH . '/application/configuration/views/';

		//Se guardar� el cache
		$this->cache_dir          = APP_PATH . '/application/temp/cache/';

	}
}
?>