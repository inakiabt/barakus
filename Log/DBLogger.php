<?php
    define('LOG_DB_CREATE_TABLE', 'CREATE TABLE IF NOT EXISTS `logs`
								   (
								   	`date` TIMESTAMP NOT NULL,
								   	`type` TINYINT NOT NULL,
									`msg` VARCHAR(255) NOT NULL
								   ); ');
	class DBLogger extends Logger
    {
    	var $db;

    	function DBLogger($DBManager)
        {
			$this->db   = $DBManager;
            $this->db->Execute(LOG_DB_CREATE_TABLE);
        }

        function logMessage($msg, $type)
        {
        	$TYPE = $this->getTypeName($type);

            $this->db->Execute("INSERT INTO logs (date, type, msg) VALUES (".time().", ".$TYPE.", '".$msg."');");
        }
    }
?>