<?php

/**
 * Clase Model
 *
 * @version $Id$
 * @copyright 2006
 */
class Model
{
	private static $model  = null;
	var $DBManager = null;
    
    function getModelClassName($model)
    {
        if (empty($model))
        {
            Error::ModelEmpty();
        }
              
        if ($model == 'DAO')
        {
            Barakus::import('Barakus.DB.Model.DAO');
        } else {
            Barakus::import(Config::get("model_dir_real") . "." . $model);
        }
        return $model.'Model';
    }
    
    function getModel($model)
    {
        $class = Model::getModelClassName($model);
        return new $class;
    }

    public static function getInstance()
    {
        if(self::$model == null)
        {
            $class = __CLASS__;
            self::$model = new $class;
        }
        return self::$model;
    }

    function getModelInstance($model)
    {
        $class = Model::getModelClassName($model);
        $inst = null;
        eval("\$inst = $class::getInstance();");
        return $inst;
    }

	function getDBManager($name = "")
	{
		$var_name = empty($name) ? 'db' : 'db_' . $name;
		if ($this->DBManager == null)
		{
			$this->DBManager = new DBManager();
		}
		return $this->DBManager->getInstance($name);
	}
}
?>