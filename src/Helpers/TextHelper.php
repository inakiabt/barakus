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
                '�'=>'a','�'=>'a','�'=>'a','�'=>'a',
                '�'=>'A','�'=>'A','�'=>'A','�'=>'A',
                '�'=>'e','�'=>'e','�'=>'e','�'=>'e',
                '�'=>'E','�'=>'E','�'=>'E','�'=>'E',
                '�'=>'i','�'=>'i','�'=>'i','�'=>'i',
                '�'=>'I','�'=>'I','�'=>'I','�'=>'I',
                '�'=>'o','�'=>'o','�'=>'o','�'=>'o',
                '�'=>'O','�'=>'O','�'=>'O','�'=>'O',
                '�'=>'u','�'=>'u','�'=>'u','�'=>'u',
                '�'=>'U','�'=>'U','�'=>'U','�'=>'U',
                '�'=>'n','$'=>'s','?'=>'','&'=>'',
                '�'=>'N','$'=>'S','�'=>'','&'=>'',
                '�'=>'c','�'=>'y','�'=>'C','�'=>'Y',
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
