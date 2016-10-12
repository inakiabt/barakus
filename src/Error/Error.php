<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2006
 */

	class Error
	{
		function getType($tipo)
		{
	        $errType = array (
	        	0    => "Fatal Error",
	            1    => "Php Error",
	            2    => "Php Warning",
	            4    => "Parsing Error",
	            8    => "Php Notice",
	            16   => "Core Error",
	            32   => "Core Warning",
	            64   => "Compile Error",
	            128  => "Compile Warning",
	            256  => "Barakus Error",
	            512  => "Php User Warning",
	            1024 => "Php User Notice",
                4096 => "Barakus Error",
                8192 => "Exception"
	        );
			return $errType[$tipo];
		}
        function Fire($msg)
        {
            trigger_error($msg, E_USER_ERROR);
        }
		function NotIntVal($key, $exit = true)
		{
			trigger_error("Se esperaba un valor entero: '$key' => " . Request::get($key), E_USER_ERROR);
		}
		function GetParamConfigEmpty($exit = true)
		{
			trigger_error("Parametro de configuracion vacío", E_USER_ERROR);
		}
		function FileNotFound($file, $exit = true)
		{
            debug_print_backtrace();
			trigger_error("Archivo no encontrado: $file", E_USER_ERROR);
		}
		function DirNotFound($dir, $exit = true)
		{
			trigger_error("Directorio no encontrado: $dir", E_USER_ERROR);
		}
        function CantCreateDir($dir, $exit = true)
        {
        	trigger_error("No se puede crear el directorio: " . $dir, E_USER_ERROR);
        }
        function LogErrorMailTypeNotAllowed($exit = true)
        {
        	trigger_error("No se permite Log de tipo Mail por default");
        }
		function InvalidAction($action, $exit = true)
		{
			trigger_error("Accion invalida: $action", E_USER_ERROR);
		}
		function ModelEmpty($exit = true)
		{
			trigger_error("Modelo vacio", E_USER_ERROR);
		}
		function ClassNotExists($class, $exit = true)
		{
			trigger_error("Clase inexistente: $class", E_USER_ERROR);
		}
		function ApplicationDirNotExists($exit = true)
		{
			trigger_error("Directorio de Aplicacion inexistente, debe estar situada en: " . APP_PATH . "/Application", E_USER_ERROR);
		}
		function LocationEmpty($exit = true)
		{
			trigger_error("Location Vacio en Controller::redirect()", E_USER_ERROR);
		}
        function InvalidLogPriority($priority, $exit = true)
        {
            trigger_error("Prioridad Log inválida: " . $priority, E_USER_ERROR);
        }
        function LayoutNotExists($layout, $exit = true)
        {
            trigger_error("Layout inexistente: \"$layout\"", E_USER_ERROR);
        }
        function ArrayExpected($exit = true)
        {
            trigger_error("Se esperaba un arreglo", E_USER_ERROR);
        }
        function CustomValidationInvalid($custom, $exit = true)
        {
            trigger_error("Validador custom no existe. Debe crear el metodo '$custom(\$data)' en el Controller correspondiente.", E_USER_ERROR);
        }
	}

?>