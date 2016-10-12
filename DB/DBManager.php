<?php

class DBManager
{
	function getDriverPath($driver)
	{
		switch($driver)
		{
            case "doctrine" :            return "doctrine";
            case "adodb-lite" :          return "adodb-lite";
			case "adodb" :
			case "adodb-active-record" : return "adodb";
		}
	}

	function getClassManager($manager)
	{
		switch ($manager)
		{
			case "adodb-lite" :          return "Adodb_lite_Manager";
			case "adodb" :               return "Adodb_Manager";
			case "adodb-active-record" : return "Adodb_Active_Record_Manager";
            case "doctrine" :            return "DoctrineDriver_Manager";
            default:                     return "DoctrineDriver_Manager";
		}
	}
	function getInstance($name = "")
	{
		$var_name = 'instanceDB'. $name;
		if (!$this->$var_name)
		{
			$manager = Config::get("DBManager");
			$classManager = $this->getClassManager($manager);

            Barakus::import("Barakus.DB.Driver");
			Barakus::import("Barakus.DB." . $classManager);

			$name = empty($name) ? $name : $name . "_";
			$this->$var_name = new $classManager();
            $this->$var_name->setServer(Config::get("db_" . $name . "server"));
            $this->$var_name->setUser(Config::get("db_" . $name . "user"));
            $this->$var_name->setPassword(Config::get("db_" . $name . "pass"));
            $this->$var_name->setDBName(Config::get("db_" . $name . "dbname"));
            $this->$var_name->setDriver(Config::get("db_" . $name . "driver"));
            $this->$var_name->connect();
		}
		return $this->$var_name;
	}
}

?>