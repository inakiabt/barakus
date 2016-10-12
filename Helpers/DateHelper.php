<?php
    class DateHelper
    {
        function arrayToTimestamp($array, $hora = 1, $mins = 1, $segs = 1)
        {
            if (is_array($array['Day']))
            {
                $return = array();
                foreach ($array['Day'] as $index => $day)
                {
                    $return[] = mktime((is_array($hora) ? $hora[$index] : $hora), $mins, $segs, $array['Month'][$index], $array['Day'][$index], $array['Year'][$index]);
                }
            } else {
                $return = mktime($hora, $mins, $segs, $array['Month'], $array['Day'], $array['Year']);
            }
            return $return;
        }
        
        function timestampToArray($date, $indices = false)
        {
            if (is_array($date))
            {
                $return = array();
                if (!$indices)
                {
                    $indices = array_keys($date);
                }
                foreach ($date as $index => $fecha)
                {
                    $return['Day'][$indices[$index]]   = date("d", $fecha);
                    $return['Month'][$indices[$index]] = date("m", $fecha);
                    $return['Year'][$indices[$index]]  = date("Y", $fecha);
                    $return['Hour'][$indices[$index]]  = date("H", $fecha);
                    $return['Mins'][$indices[$index]]  = date("i", $fecha);
                    $return['Segs'][$indices[$index]]  = date("s", $fecha);
                }
            } else {
                $return['Day']   = date("j", $date);
                $return['Month'] = date("n", $date);
                $return['Year']  = date("Y", $date);
                $return['Hour']  = date("H", $date);
                $return['Mins']  = date("i", $date);
                $return['Segs']  = date("i", $date);
            }
            return $return;
        }
    }
?>
