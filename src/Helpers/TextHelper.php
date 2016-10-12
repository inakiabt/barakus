<?php
    class TextHelper
    {
        function sanitize($str)
        {
            // blog charset
            static $charset = 'UTF-8';

            // string separator
            $sep = '-';

            // character translation table
            $chars = array
                (
                'á'=>'a','à'=>'a','â'=>'a','ä'=>'a',
                'Á'=>'A','À'=>'A','Â'=>'A','Ä'=>'A',
                'é'=>'e','è'=>'e','ê'=>'e','ë'=>'e',
                'É'=>'E','È'=>'E','Ê'=>'E','Ë'=>'E',
                'í'=>'i','ì'=>'i','î'=>'i','ï'=>'i',
                'Í'=>'I','Ì'=>'I','Î'=>'I','Ï'=>'I',
                'ó'=>'o','ò'=>'o','ô'=>'o','ö'=>'o',
                'Ó'=>'O','Ò'=>'O','Ô'=>'O','Ö'=>'O',
                'ú'=>'u','ù'=>'u','û'=>'u','ü'=>'u',
                'Ú'=>'U','Ù'=>'U','Û'=>'U','Ü'=>'U',
                'ñ'=>'n','$'=>'s','?'=>'','&'=>'',
                'Ñ'=>'N','$'=>'S','¿'=>'','&'=>'',
                'ç'=>'c','ý'=>'y','Ç'=>'C','Ý'=>'Y',
                '<'=>'','>'=>''
                );

            // lowercase trying to preserver charset
            if(!function_exists('mb_strtolower')) $str = strtolower($str);
            else $str = mb_strtolower($str, $charset);

            // strips tags and fix encoded chars
            $str = trim(strip_tags(urldecode($str)));

            // convert disallowed chars into allowed
            foreach ($chars as $no => $yes) $str = str_replace($no, $yes, $str);

            // replaces non allowed chars into spaces
            $str = preg_replace('/[^a-z0-9' . implode('', $chars) . ']/ui', ' ', $str);

            // delete remaining spaces
            $str = preg_replace('/\s+/', $sep , str_replace('+', ' ', $str));

            // replaces spaces with default separator
            $str = preg_replace("/(^$sep|$sep$)/", '', str_replace(' ', $sep, $str));

            return $str;
        }        
    }
?>
