<?php

	class MailLogger extends Logger
    {
    	var $mails     = array();
        var $__from;
        var $__subject;

    	function MailLogger($mail, $from, $subject)
        {
        	if (is_array($mail))
            {
            	$this->mails = $mail;
            } else {
            	$this->mails = array($mail);
            }
            $this->__from    = $from;
            $this->__subject = $subject;
        }

        function addMail($mail)
        {
        	$this->mails[] = $mail;
        }

        function logMessage($msg, $type)
        {
        	$TYPE = $this->getTypeName($type);
            $date = date(LOG_DATE_FORMAT);
            $MSG  = $date . ' [' . $type . '] ' . $msg . '\n';

			foreach ($this->mails as $mail)
            {
            	$this->__log2Mail($mail, $msg);
            }
        }

        function __log2Mail($mail, $msg)
        {
        	mail($mail, $this->__subject, $msg, 'From: ' . $this->__from);
        }
    }
?>