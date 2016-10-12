<?php
    class BoxSession
    {
        var $name;
        var $session;
        private static $instance = null;
        
         function BoxSession($name)
        {
            $this->name    = $name;
            $this->session = new Session();
        }
        
        public static function getInstance($name)
        {
            if (!isset(self::$instance[$name]))
            {
                self::$instance[$name] = new BoxSession($name);
            }
            return self::$instance[$name];
        }                                      
        
        function getNameVar($var)
        {
            return 'BoxSession.' . $this->name . '.' . $var;
        }
        
        function add($key, $value)
        {
            $this->session->set($this->getNameVar($key), $value);
        }
        
        function remove($key)
        {
            $this->session->reset($this->getNameVar($key));
        }
        
        function set($var, $value)
        {
            $this->__set($var, $value);
        }
        
        function __set($var, $value)
        {
            $this->session->set($this->getNameVar($var), $value);
        }
        
        function __get($var)
        {
            return $this->session->get($this->getNameVar($var));
        }

        function get($var)
        {
            return $this->__get($var);
        }
        
        function __call($method, $var)
        {
            if (method_exists($this->session, $method))
            {
                return call_user_func_array(array($this->session, $method), $this->getNameVar($var[0]));
            }
            return null;
        }
        
        function erase()
        {
            $this->session->reset('BoxSession.' . $this->name, null);
        }
        
        function toArray()
        {
            return $this->session->get('BoxSession.' . $this->name);
        }
    }
?>