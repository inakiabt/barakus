<?php
    class Driver
    {
        var $server;
        var $user;
        var $pass;
        var $dbname;
        var $driver;
        
        function setServer($server)
        {
            $this->server = $server;
        }
        
        function setUser($user)
        {
            $this->user = $user;
        }
        
        function setPassword($password)
        {
            $this->pass = $password;
        }
        
        function setDBName($dbname)
        {
            $this->dbname = $dbname;
        }
        
        function setDriver($driver)
        {
            $this->driver = $driver;
        }
        
        function connect()
        {
            die('needs to implements this method');
        }
    }
?>
