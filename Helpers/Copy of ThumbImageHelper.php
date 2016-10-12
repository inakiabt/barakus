<?php
define('DEBUG',      1);
define('ERROR',      2);
define('INCOMPLETO', 3);
define('INICIALES',  4);

class ThumbImageHelper
{
	var $calidad         = 75;
    var $size_max        = 3072000; //en bytes
    var $upload          = true;
    var $random_name     = true;
    var $rename_original = false;
    var $formatos        = array('jpeg', 'jpg', 'gif', 'png');
    var $root;

    var $error           = false;
    var $debug           = false;

    var $path_original   = '/';
    var $thumb           = array(); //width-> (0 para no dimensionar con respecto)
    								//height-> (0 para no dimensionar con respecto)
                                    //path-> con '/' final

    function Thumb($debug_iniciales = false)
    {
    	$this->root = $_SERVER['DOCUMENT_ROOT'] . '/';
    	$this->verificar();

       	$this->debug_log('Funciones necesarias instaladas', INICIALES);
    	$this->debug_log('Upload: '             . $this->BooleanToString($this->upload), INICIALES);
    	$this->debug_log('Renombrar original: ' . $this->BooleanToString($this->rename_original), INICIALES);
    	$this->debug_log('Nombre random: '      . $this->BooleanToString($this->random_name), INICIALES);
    	$this->debug_log('Tamaño máximo: '      . $this->size_max . ' bytes', INICIALES);

    	$this->debug_log('Formatos permitidos: ' . implode(', ', $this->formatos), INICIALES);
        $this->debug = $debug_iniciales;
        $this->debug();
    }
	function crear_semilla()
	{
	   list($usec, $sec) = explode(' ', microtime());
	   return (float) $sec + ((float) $usec * 100000);
	}

	function nombre()
	{
	    srand($this->crear_semilla());

	    // Generamos la clave
	    $clave="";
	    $max_chars = round(rand(8,8));  // tendrá entre 8 y 8 caracteres
	    $chars = array();
	    for ($i="a"; $i<"z"; $i++) $chars[] = $i;  // creamos vector de letras
	    $chars[] = "z";
	    for ($i=0; $i<$max_chars; $i++) {
	      $letra = round(rand(0, 1));  // primero escogemos entre letra y número
	      if ($letra) // es letra
	        $clave .= $chars[round(rand(0, count($chars)-1))];
	      else // es numero
	        $clave .= round(rand(0, 9));
	    }

	    return $clave;
	}

    function verificar()
    {
    	if (!function_exists('getimagesize'))
        {
        	echo ('Función "GetImageSize" no existe!');
        }
    	if (!function_exists('imagecreatefromjpeg'))
        {
        	echo ('Función "ImageCreateFromJpeg" no existe!');
        }
    	if (!function_exists('imagecreatefromgif'))
        {
        	echo ('Función "ImageCreateFromGif" no existe!');
        }
    	if (!function_exists('imagecreatefrompng'))
        {
        	echo ('Función "ImageCreateFromPng" no existe!');
        }
    	if (!function_exists('imagecreatefromjpeg'))
        {
        	echo ('Función "ImageCreateFromJpeg" no existe!');
        }
    	if (!function_exists('image_type_to_mime_type'))
        {
        	echo ('Función "image_type_to_mime_type" no existe!');
        }
    	if (!function_exists('imagecreatetruecolor'))
        {
        	echo ('Función "ImageCreateTrueColor" no existe!');
        }
    	if (!function_exists('imagecopyresized'))
        {
        	echo ('Función "ImageCopyResized" no existe!');
        }
    	if (!function_exists('ImageJPEG'))
        {
        	echo ('Función "ImageJPEG" no existe!');
        }
    	if (!function_exists('ImageGIF'))
        {
        	echo ('Función "ImageGIF" no existe!');
        }
    	if (!function_exists('ImagePNG'))
        {
        	echo ('Función "ImagePNG" no existe!');
        }
    	if (!function_exists('ImageDestroy'))
        {
        	echo ('Función "ImageDestroy" no existe!');
        }
    }

    function checkPath()
    {
    	if (!eregi('[\/|\\]$', $this->path_original))
        {
        	$this->path_original = $this->path_original . '/';
        }
    }

    function BooleanToString($bool)
    {
    	if ($bool)
        {
        	return 'true';
        } else {
        	return 'false';
        }
    }

    function empty_logs()
    {
    	$this->log       = array();
    	$this->error_log = array();
    }

    function setThumb($thumb)
    {
		if (!is_dir($this->root . $thumb['path']))
        {
        	$this->error = true;
            $this->error_log('El directorio "' . $this->root . $thumb['path'] . '" no existe', __LINE__);

            $this->error(true);
        }
		if (!is_writable($this->root . $thumb['path']))
        {
        	$this->error = true;
            $this->error_log('El directorio "' . $this->root . $thumb['path'] . '" no tiene permisos de escritura, reviselos', __LINE__);

            $this->error(true);
        } else {
        	$this->thumb[] = $thumb;
        }
    }

	function Crear($imagen)
	{
    	$this->empty_logs();

        $this->checkPath();

    	$this->debug_log('Path: '               . $this->path_original, INICIALES);

        $name      = $imagen['name'];
       	$this->debug_log('Se inicia el proceso de creacion de thumbnails de la imagen: "' . $name . '"');

        $size      = $imagen['size'];
       	$this->debug_log('Tamaño de la imagen: ' . $imagen['size'] . ' bytes');

        $tmp_name  = $imagen['tmp_name'];
       	$this->debug_log('Nombre temporal de la imagen: "' . $tmp_name . '"');

       	$this->debug_log('Se procede a checkear la existencia de la imagen original');
        if (!$this->upload && !file_exists($this->root .  $this->path_original . $name))
        {
            $this->error_log('Imagen original no existe: ' . $this->root .  $this->path_original . $name, __LINE__);
            return $info;
        }
        if (!file_exists($tmp_name))
        {
            $this->error_log('Imagen temporal no existe: ' . $tmp_name, __LINE__);
            return $info;
        }
       	$this->debug_log('La imágen es correcta');

        $ext       = explode(".", $name);
        $extension = strtolower($ext[count($ext)-1]);

        $info['nombre']    = $name;
        $info['size']      = $size;
        $info['extension'] = $extension;

        $si = in_array($extension, $this->formatos);

        if (!$si && $extension != '')
        {
            //ERROR POR EXTENSION NO SOPORTADA
            $this->error_log('Extension no soportada: '.$this->root .  $this->path_original .$name, __LINE__);
            $info['error'] = 'Extension no soportada: '.$this->root .  $this->path_original .$name;
            return $info;
        } elseif ($size > $this->size_max){
            //ERROR POR TAMAÑO MUY GRANDE
            $this->error_log('Tamaño de imagen muy grande: '.$this->root .  $this->path_original . $nombre_imagen, __LINE__);
            $info['error'] = 'Tamaño de imagen muy grande: '.$this->root .  $this->path_original . $nombre_imagen;
            return $info;
        } else {
        	$this->debug_log('Extension "' . $extension . '" soportada');
        	$this->debug_log('Tamaño de imágen ' . $size . ' bytes dentro de los limites permitidos');

            if ($this->random_name)
            {
                $nombre_imagen = $this->nombre() . "." . $extension;
	        	$this->debug_log('Creando nombre aleatorio: ' . $nombre_imagen);
            } else {
                $nombre_imagen = $name;
            }
            $info['nombre_imagen'] = $nombre_imagen;

        	$this->debug_log('Obteniendo dimensiones de la imagen temporal');
            $mime = @GetImageSize ($tmp_name);
            if (!$mime)
            {
                $this->error_log('Error al obtener datos de la imagen: '.$tmp_name, __LINE__);
                $info['error'] = 'Error al obtener datos de la imagen: '.$tmp_name;
                return $info;
            }

        	$this->debug_log('Dimensiones: ' . $mime[0] . ' px de Ancho  -  '.$mime[1] . ' px de Alto');
            $info['dimensiones'] = $mime[0] . ' px de Ancho  -  '.$mime[1] . ' px de Alto';

        	$this->debug_log('Obteniendo imagen original');
            if ($this->upload)
            {
                $imagen_subida = @move_uploaded_file($tmp_name, $this->root . $this->path_original .$nombre_imagen);
                $nombre        = $nombre_imagen;
            } else {
                $imagen_subida = 1;
                $nombre        = $name;
            }
            if($imagen_subida)
            {
	        	$this->debug_log('Obteniendo tipo de la imagen original');

                $imginfo = @getimagesize($this->root .  $this->path_original . $nombre);

	        	$this->debug_log('Tipo: ' . $imginfo['mime']);

                if ($imginfo['mime']       == @image_type_to_mime_type(IMAGETYPE_JPEG))
                {
                    $im = @imagecreatefromjpeg($this->root .  $this->path_original .$nombre);
                    $info['type'] = 'jpg';
                } elseif ($imginfo['mime'] == @image_type_to_mime_type(IMAGETYPE_GIF))
                {
                    $im = @imagecreatefromgif($this->root .  $this->path_original .$nombre);
                    $info['type'] = 'gif';
                } elseif ($imginfo['mime'] == @image_type_to_mime_type(IMAGETYPE_PNG))
                {
                    $im = @imagecreatefrompng($this->root .  $this->path_original .$nombre);
                    $info['type'] = 'png';
                } else {
                    //ERROR SI LA IMAGEN REALMENTE ES DE UN TIPO NO SOPORTADO
                    $this->error_log('Tipo de imagen no soportado: '.$this->root .  $this->path_original .$nombre, __LINE__);
                    $info['error'] = 'Tipo de imagen no soportado: '.$this->root .  $this->path_original .$nombre;
                    return $info;
                }

	        	$this->debug_log('Se procede a crear los thumbnails');

                $errores = 0;
                foreach ($this->thumb as $thmb)
                {
		        	$this->debug_log('Creando thumb de dimensiones: ' . $thmb['width'] . 'x' .$thmb['height'] . ' máximo en "' . $this->root . $thmb['path'] . $nombre_imagen . '"');
                	$sin_error = @$this->subir($thmb['width'], $thmb['height'], $thmb['path'], $nombre_imagen, $mime, $imginfo, $im);
                    if ($sin_error === false)
                    {
                    	$errores++;
                    } else {
                    	if (!file_exists($this->root . $thmb['path'] . $nombre_imagen))
                        {
		                    $this->error_log('No se pudo redimensionar la imagen: '.$this->root .  $this->path_original .$nombre, __LINE__, INCOMPLETO);
	                    	$errores++;
                        } else {
	                        list($width, $height) = @getimagesize($this->root . $thmb['path'] . $nombre_imagen);
	                        $this->debug_log('Thumb "' . $this->root . $thmb['path'] . $nombre_imagen . '" ' . $width . 'x' . $height . ' creado con éxito');
	                        $info['thumbs'][] = '"' . $this->root . $this->path_original . $thmb['path'] . $nombre_imagen . '" ' . $width . 'x' . $height;
                        }
                    }
                }

                if ($errores > 0)
                {
		        	$this->debug_log('Thumbs creados con éxito');
                }

                @ImageDestroy($im);
                if ($errores == 0 && $this->rename_original)
                {
		        	$this->debug_log('Renombrando imagen original');
                    if (!@rename($this->root . $this->path_original . $name, $this->root . $this->path_original . $nombre_imagen))
                    {
                    	$errores++;
                    	$this->error_log('No se pudo renombrar la imagen original, compruebe la ruta y los permisos.', __LINE__);
                    }
                }

                if ($errores == 0)
                {
		        	$this->debug_log('Proceso finalizado sin errores');
                } else {
		        	$this->debug_log('Proceso finalizado con errores', ERROR);
                }

                $this->debug();

                return $info;
            } else {
                $this->error_log('Error al subir la imagen, revise el path: '.$this->root . $this->path_original, __LINE__);

                $this->debug();

                return $info;
            }
        }
	}

	function subir($ancho, $altura, $path_dimensionado, $nombre, $mime, $imginfo, $im)
	{
	    $srcAncho  = $imginfo[0];
	    $srcAltura = $imginfo[1];

        $return_error = array();

	    if (($srcAncho > $srcAltura && $ancho != 0) || $altura == 0){
	        //Redimensiono con respecto al ancho
	        $ratioAncho = $srcAncho / $ancho;
	        $destAncho = $ancho;
	        $destAltura = $srcAltura / $ratioAncho;
	    } elseif (!($srcAncho > $srcAltura && $ancho != 0)) {
	        //Redimensiono con respecto al alto
	        $ratioAltura = $srcAltura / $altura;
	        $destAltura = $altura;
	        $destAncho = $srcAncho / $ratioAltura;
	    } else {
	        $this->error_log('Problema con las dimensiones de la imagen', __LINE__);
	        return false;
	    }

	    $imagen_true_color = @imagecreatetruecolor($destAncho, $destAltura);
	    if (!$imagen_true_color)
	    {
	        $this->error_log('Problema con las dimensiones de la imagen', __LINE__);
	        return false;
	    }
	    if (!@imagecopyresized($imagen_true_color   , $im   , 0   , 0   , 0   , 0   , $destAncho, $destAltura, $srcAncho, $srcAltura))
	    {
	        $this->error_log('Problema al crear la imagen', __LINE__);
	        return false;
	    }

	    if ($mime['mime'] == image_type_to_mime_type(IMAGETYPE_JPEG))
	    {
	        $imagen = @ImageJPEG($imagen_true_color, $this->root . $path_dimensionado .$nombre, $this->calidad);  //75 % de compresion (pareciera)
	    } elseif ($mime['mime'] == @image_type_to_mime_type(IMAGETYPE_GIF))
	    {
	        $imagen = @ImageGIF($imagen_true_color, $this->root . $path_dimensionado .$nombre);
	    } elseif ($mime['mime'] == @image_type_to_mime_type(IMAGETYPE_PNG))
	    {
	        $imagen = @ImagePNG($imagen_true_color, $this->root . $path_dimensionado .$nombre);
	    } else {
	        $this->error_log('Problema al crear la imagen', __LINE__);
	        return false;
	    }

        if (!$imagen)
        {
	        $this->error_log('No se puede abrir "'.$this->root . $path_dimensionado . $nombre.'" para escritura', __LINE__);
            return false;
        }
	    return true;
	}

    function debug_log($texto, $tipo = DEBUG)
    {
    	switch($tipo)
        {
        	case ERROR:     $texto = '<b style="color: red">[ERROR]' . $texto . '</b>'; break;
        	case DEBUG:     $texto = '<b style="color: #4D932F">' . $texto . '</b>'; break;
        	case INICIALES: $texto = '<b style="color: #0078B3">' . $texto . '</b>'; break;
        }
    	$this->log[] = $texto;
    }

    function error_log($error, $linea, $tipo_error = ERROR)
    {
    	if (!empty($error))
        {
        	if ($tipo_error == INCOMPLETO)
            {
            	$error = '<b style="color: red">' . $error . '</b>';
            }
            $error_armado      = 'Línea '.$linea.': '.$error;
            $this->error       = true;
    		$this->error_log[] = $error_armado;
            $this->debug_log($error_armado, ERROR);
            return 1;
        }
        return 0;
    }

    function error($mostrar = false)
    {
    	$return = 'ERRORES';
    	if (count($this->error_log) == 0)
        {
        	return;
        }
        $return .= '<ul class="error">';
    	foreach ($this->error_log as $error)
        {
			$return .= '<li><b style="color: red">[ERROR]' . $error . '</b></li>';
        }
        $return .= '</ul>';

        if ($mostrar)
        {
        	 echo ($return);
             return;
        }
        return $return;
    }

    function debug()
    {
    	if (!$this->debug)
        {
        	return;
        }
    	$return = 'DEBUG';
    	if (count($this->log) == 0)
        {
        	return;
        }
        $return .= '<ul class="error">';
    	foreach ($this->log as $debug)
        {
			$return .= '<li>' . $debug . '</li>';
        }
        $return .= '</ul>';

        echo ($return);
    }
}
?>