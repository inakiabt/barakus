<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2007
 */
class ControllerPlugin
{
	function ControllerPlugin()
	{
	}

	function preLoad()
	{
	}

	function onLoad()
	{
	}

	function init()
	{
	}

	function end()
	{
	}
    
    function import($file)
    {
        return Barakus::import(Config::get('plugins_dir_real') . '.Controller.' . $file);
    }
    function setIsAJAXCallBack($is)
    {
        $this->isAJAXCallBack = $is;
    }    
}

?>