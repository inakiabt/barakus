<?php
/**
* Smarty ucwords modifier plugin
*
* Type: ucwords<br>
* Name: capitalize<br>
* Purpose: capitalize words in the string
* Example: {$text|ucwords}
* @author SzubertX <szubertx at tlen dot pl>
* @param string
* @return string
*/
function smarty_modifier_ucwords($string)
{
return ucwords(strtolower($string));
}

?>