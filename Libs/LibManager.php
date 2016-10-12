<?php

class LibManager
{
	function get($lib)
	{
		LibManager::import($lib);

		$className = Barakus::lastDeclaredClass();
        
		return new $className;

	}
	function import($lib)
	{
		Barakus::import("Barakus.Libs." . $lib);
	}
}

?>