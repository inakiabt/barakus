<?php
    class EventManager
    {
        function getEventInstance($eventName, $func, $params, $pre_post = array())
        {
            Barakus::import('Barakus.Events.' . $eventName, true);
            
            return new $eventName($func, $params, $pre_post);
        }
    }
?>
