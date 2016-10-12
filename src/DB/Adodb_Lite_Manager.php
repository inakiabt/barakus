<?php
Barakus::import("Barakus.DB.Drivers.adodb_lite.adodb.inc.php");
class Adodb_Lite_Manager extends Driver
{
	var $connection;
	function connect($persistent = false)
	{
        $dsn = "{$this->driver}://{$this->user}:{$this->pass}@{$this->server}/{$this->dbname}";
        if ($persistent)
        {
            $dsn .= "?persist";
        }
		$this->connection = ADONewConnection($dsn);
	}
}
?>