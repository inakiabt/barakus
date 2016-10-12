<?php

class Install extends Controller
{
	function Install()
	{
	}

	function init()
	{
		$self = substr($_SERVER['PHP_SELF'], 1);
		$this->view('install');
		$this->view->assign("root_path", $_SERVER['DOCUMENT_ROOT']);
		$this->view->assign("dir_name",  substr(dirname($self), 0, -8));
		$this->view->show();
	}
}

?>