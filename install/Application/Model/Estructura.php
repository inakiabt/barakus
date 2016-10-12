<?php
class EstructuraModel extends Model
{
	var $files    = array();
	var $archivos = array();

	function EstructuraModel()
	{
	}

	function defines($dir)
	{
		define('APPLICATION_DIR', $dir . '/Application');
		define('MODEL_DIR',       APPLICATION_DIR . '/Model');
		define('VIEW_DIR',        APPLICATION_DIR . '/View');
		define('DBCLASSES_DIR',   APPLICATION_DIR . '/DBClasses');
		define('ERROR_DIR',       VIEW_DIR . '/Error');
		define('DEBUG_ERROR_FILE',ERROR_DIR . '/debug.tpl');
		define('ONLINE_ERROR_FILE',ERROR_DIR . '/online.tpl');
		define('CONTROLLER_DIR',  APPLICATION_DIR . '/Controller');
		define('TEMP_DIR',        APPLICATION_DIR . '/Temp');
		define('LOGS_DIR',        APPLICATION_DIR . '/Logs');
		define('JS_DIR',          $dir . '/js');
		define('COMPILE_DIR',     TEMP_DIR . '/Compile');
		define('CACHE_DIR',       TEMP_DIR . '/Cache');
		define('CONFIGURATION_FILE', APPLICATION_DIR . '/configuration.xml');
		define('INDEX_FILE',      $dir . '/index.php');

		Session::set('INSTALL.P_0',  array('nombre' => 'Applicacion', 'type' => 'dir',  'path' => APPLICATION_DIR));
		Session::set('INSTALL.P_1',  array('nombre' => 'Modelos'    , 'type' => 'dir',  'path' => MODEL_DIR));
		Session::set('INSTALL.P_2',  array('nombre' => 'Vistas'     , 'type' => 'dir',  'path' => VIEW_DIR));
		Session::set('INSTALL.P_3',  array('nombre' => 'Clases BD',   'type' => 'dir',  'path' => DBCLASSES_DIR));
		Session::set('INSTALL.P_4',  array('nombre' => 'Errores',     'type' => 'dir',  'path' => ERROR_DIR));
		Session::set('INSTALL.P_5',  array('url' => 'http://structurebarakus.googlecode.com/svn/trunk/Application/View/Error/debug.tpl', 'nombre' => 'Vista debug', 'type' => 'file', 'path' => DEBUG_ERROR_FILE));
		Session::set('INSTALL.P_6',  array('url' => 'http://structurebarakus.googlecode.com/svn/trunk/Application/View/Error/online.tpl', 'nombre' => 'Vista online','type' => 'file', 'path' => ONLINE_ERROR_FILE));
		Session::set('INSTALL.P_7',  array('nombre' => 'Controllers', 'type' => 'dir',  'path' => CONTROLLER_DIR));
		Session::set('INSTALL.P_8',  array('nombre' => 'Archivos Temporales', 'type' => 'dir',  'path' => TEMP_DIR));
		Session::set('INSTALL.P_9',  array('nombre' => 'Logs',        'type' => 'dir',  'path' => LOGS_DIR));
		Session::set('INSTALL.P_10', array('nombre' => 'Javascript',  'type' => 'dir',  'path' => JS_DIR));
		Session::set('INSTALL.P_11', array('nombre' => 'Compilaciones','type'=> 'dir',  'path' => COMPILE_DIR));
		Session::set('INSTALL.P_12', array('nombre' => 'Cache',       'type' => 'dir',  'path' => CACHE_DIR));
		Session::set('INSTALL.P_13', array('url' => 'http://structurebarakus.googlecode.com/svn/trunk/Application/configuration.xml', 'nombre' => 'Configuracion','type'=> 'file', 'path' => CONFIGURATION_FILE));
		Session::set('INSTALL.P_14', array('url' => 'http://structurebarakus.googlecode.com/svn/trunk/index.php', 'nombre' => 'Index','type'=> 'file', 'path' => INDEX_FILE));
		Session::set('INSTALL.P_15', array('url' => 'http://structurebarakus.googlecode.com/svn/trunk/js/AC_RunActiveContent.js', 'nombre' => 'Patch Flash','type'=> 'file', 'path' => JS_DIR . '/AC_RunActiveContent.js'));
		Session::set('INSTALL.P_16', array('url' => 'http://structurebarakus.googlecode.com/svn/trunk/js/prototype.js', 'nombre' => 'Prototype','type'=> 'file', 'path' => JS_DIR . '/prototype.js'));
		Session::set('INSTALL.P_17', array('url' => 'http://structurebarakus.googlecode.com/svn/trunk/js/scriptaculous.js', 'nombre' => 'Scriptaculous','type'=> 'file', 'path' => JS_DIR . '/scriptaculous.js'));
		Session::set('INSTALL.P_18', array('url' => 'http://structurebarakus.googlecode.com/svn/trunk/js/dragdrop.js', 'nombre' => 'DragDrop','type'=> 'file', 'path' => JS_DIR . '/dragdrop.js'));
		Session::set('INSTALL.P_19', array('url' => 'http://structurebarakus.googlecode.com/svn/trunk/js/effects.js', 'nombre' => 'Effectss','type'=> 'file', 'path' => JS_DIR . '/effects.js'));
		Session::set('INSTALL.P_20', array('url' => 'http://structurebarakus.googlecode.com/svn/trunk/js/builder.js', 'nombre' => 'Builder','type'=> 'file', 'path' => JS_DIR . '/builder.js'));
		Session::set('INSTALL.P_21', array('url' => 'http://structurebarakus.googlecode.com/svn/trunk/js/controls.js', 'nombre' => 'Controls','type'=> 'file', 'path' => JS_DIR . '/controls.js'));
		Session::set('INSTALL.P_22', array('url' => 'http://structurebarakus.googlecode.com/svn/trunk/js/slider.js', 'nombre' => 'Slider','type'=> 'file', 'path' => JS_DIR . '/slider.js'));
	}

	function getAll($dir)
	{
		$dir = $_SERVER['DOCUMENT_ROOT'] . '/' . $dir;
		if (!file_exists($dir))
		{
			return $this->error("Path inexistente! HALT!");
		}
		$this->defines($dir);
		$install = Session::get('INSTALL');
/*		$install_keys = array_keys($install);
		$array = array();
		foreach ($install_keys as $i)
		{
			$array[$i] = $install[$i]['nombre'];
		}
*/
		return $install;
	}

	function create($id, $sobreescribir = false)
	{
		$request = Session::get("INSTALL.$id");
		if (empty($request))
		{
			return $this->error("No existe el directorio solicitado");
		}

		if ($request['type'] == 'dir')
		{
			return $this->__createDir($request);
		} else {
			return $this->__createFile($request, $sobreescribir);
		}
	}

	function __createDir($r)
	{
		if (file_exists($r['path']))
		{
			return $this->error("Ya existe el directorio solicitado, omitiendo...");
		} elseif (@mkdir($r['path']))
		{
			return $r;
		}
		else
		{
			return $this->error("No se pudo crear el directorio " . $r['path']);
		}
	}

	function __createFile($r, $sobreescribir)
	{
		if (file_exists($r['path']) && !$sobreescribir)
		{
			return array('halt' => true, 'msg' => 'Ya existe el archivo "'.$r['path'].'", ¿Desea sobreescribirlo?');
		} elseif ($fp = @fopen($r['path'], "w+"))
		{
			$data = file_get_contents($r['url']);
			fwrite($fp, $data);
			fclose($fp);
			return $r;
		}
		else
		{
			return $this->error("No se pudo crear el archivo " . $r['path']);
		}
	}

	function error($msg)
	{
		$array = array('error' => $msg);

		return $array;
	}
}

?>