<?php

	class ConsoleLogger extends Logger
    {
    	var $buffer = array();

    	function logMessage($msg, $type)
        {
        	$TYPE = $this->getTypeName($type);
            $date = date(LOG_DATE_FORMAT);
            $MSG  = $date . ' [' . $type . '] ' . $msg . '<br />';

            $this->buffer[] = $msg;
        }

        function __destroy()
        {
        ?>
        <script type="text/javascript">
	    <!--
	        attr = 'resizable=no,scrollbars=yes,width=550,height=650,screenX=300,screenY=200';
	        popWin = open('', 'new_window', attr);
	        popWin.document.write('<head><title>Log Console</title></head>');
	        popWin.document.write('<body><div align=center>');
        <?php
        	foreach ($this->buffer as $msg)
            {
            ?>
			popWin.document.write('<?=$msg?>');
            <?php
            }
        ?>
	        popWin.document.write('</div></body></html>');
	    //-->
        </script>
        <?
        }
    }
?>