<?php
function smarty_modifier_formato_fecha($fecha)
{
	$ingles  = array ("Monday", "Tuesday", "Thursday",  "Wednesday", "Friday",  "Saturday", "Sunday",
					"January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");

    $espanol = array ("Lunes",  "Martes",  "Miércoles", "Jueves",    "Viernes", "Sábado",   "Domingo",
					"Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");

    return str_replace($ingles, $espanol, $fecha);
}
?>