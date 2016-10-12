<?php
    Barakus::import('Barakus.DB.Drivers.Doctrine.Doctrine');
    spl_autoload_register(array('Doctrine', 'autoload'));
    Barakus::import('Barakus.DB.Model.Doctrine.DBDoctrineModel');       
    class DoctrineDriver_Manager extends Driver
    {
        function connect($persistent = false)
        {
            $dsn  = "{$this->driver}://{$this->user}:{$this->pass}@{$this->server}/{$this->dbname}";
            $conn = Doctrine_Manager::connection($dsn, $this->dbname);
            $conn->setAttribute('model_loading', 'conservative');  
            Doctrine::loadModels(Barakus::getPath('Barakus.DB.Model.Doctrine.Templates'));
            Doctrine::loadModels(Config::get('DBClassesPath'));
            Doctrine::loadModels(Config::get('model_dir'));
            
            Doctrine::debug(Config::get('db_debug'));
            
            if (Config::get('db_cache'))
            {
                $servers = array(
                    'host' => 'localhost',
                    'port' => 11211,
                    'persistent' => true
                );

                $cacheDriver = new Doctrine_Cache_Memcache(array(
                        'servers' => $servers,
                        'compression' => false
                    )
                );            
                
                $conn->setAttribute(Doctrine::ATTR_QUERY_CACHE, $cacheDriver);
            }
        }
    }
?>
