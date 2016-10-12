<?php

	class DisplayLogger extends Logger
    {

        function logMessage($msg, $type)
        {
        	$TYPE = $this->getTypeName($type);
            $date = date(LOG_DATE_FORMAT);
            $MSG  = $date . ' [' . $type . '] ' . $msg . '<br />';

			echo $MSG;
        }
    }
?>