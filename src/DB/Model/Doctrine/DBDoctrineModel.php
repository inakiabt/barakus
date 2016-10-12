<?php
    class DBDoctrineModel extends Doctrine_Record
    {
        public function __call($method, $arguments)
        {
            if (substr($method, 0, 4) == 'find') 
            {
                if (empty($arguments[0]))
                {
                    $arguments[0] = 'NULL';
                }
                return call_user_func_array(array($this->getTable(), $method), $arguments);
            }
            return parent::__call($method, $arguments);
        }
        
        public function hasOne($class, $args)
        {
            Barakus::import(Config::get("DBClassesPath_real") . ".Base" . $class);
            Barakus::import(Config::get("model_dir_real") . "." . $class);
            parent::hasOne($class, $args);
        }
        
        public function hasMany($class, $args)
        {
            if (eregi(' as ', $class))
            {
                $expl = explode(' as ', $class);
                $clase = trim($expl[0]);
            } else {
                $clase = $class;
            }
            Barakus::import(Config::get("DBClassesPath_real") . ".Base" . $clase);
            Barakus::import(Config::get("model_dir_real") . "." . $clase);
            parent::hasMany($class, $args);
        }
    }
?>
