<?php
class Generar extends Controller
{
	function Generar()
	{
		LibManager::import("Ajax.JSON");
		$this->JSON = new Services_JSON;
		$this->AJAX = LibManager::get("Ajax.Ajax");
		$this->model('Estructura');
	}

	function init()
	{
		$path = Post::get('path');

		$this->view('generar');
		$this->view->assign('path', $path);
		$this->view->show();
	}

	function GetFiles()
	{
		$dir = Post::get('dir');
		$response = $this->JSON->encode($this->Estructura->getAll($dir));

		$this->AJAX->sendJSON($response);
	}

	function Create()
	{
		$dir = Request::get('dir');
		$sob = Request::get('sob');

		$response = $this->JSON->encode($this->Estructura->create($dir, $sob));

		$this->AJAX->sendJSON($response);
	}
}

?>