<?php
Barakus::import("Barakus.DB.Drivers.Adodb.adodb\.inc");
Barakus::import("Barakus.DB.Drivers.Adodb.adodb-active-record\.inc");
Barakus::import('Barakus.DB.Drivers.Adodb.adodb-errorhandler\.inc');
Barakus::import("Barakus.DB.Persistent.PersistentActiveRecord");

class Adodb_Active_Record_Manager extends Driver
{
	var $connection;
    function connect($persistent = false)
	{
		$dsn = "{$this->driver}://{$this->user}:{$this->pass}@{$this->server}/{$this->dbname}";
		if ($persistent)
		{
			$dsn .= "?persist";
		}

		$this->connection = NewADOConnection($dsn);
		ADOdb_Active_Record::SetDatabaseAdapter($this->connection);
	}

	function getActiveRecord($table)
	{
		$table = ucfirst(strtolower($table));
		if (!class_exists($table) && !Barakus::import(Config::get("DBClassesPath_real") . "." . $table, true))
		{
			$class = "  class $table extends PersistentActiveRecord
						{
							//var \$_table = '".strtolower(ADOdb_Active_Record::_pluralize($table))."';   
                            var \$_table = '".strtolower($table)."';   
                            var \$_DB = null;
                            function setDB(\$db)
                            {
                                \$this->_DB = \$db;
                            }
                            function loadVars()
                            {
                            }
						}";
			eval($class);
		}

        return new $table();
	}
}
?>