<?php
    class DAOModel extends Model
    {
        public function DAOModel()
        {
            $this->getDBManager();
        }
        
        public function __get($name)
        {
            return $this->getNew($name);
        }
        
        public function getNew($dataObject)
        {
            if (empty($dataObject))
            {
                Error::ModelEmpty();
            }

            Barakus::import(Config::get("DBClassesPath_real") . ".Base" . $dataObject);
            Barakus::import(Config::get("model_dir_real") . "." . $dataObject);
            return new $dataObject();
        }
        
        public function setActiveDB($db)
        {
            $this->getDBManager($name);
        }
    }
?>
