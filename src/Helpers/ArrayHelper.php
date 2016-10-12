<?php
    class ArrayHelper
    {
        var $_array = array();
        
        function ArrayHelper($array = array())
        {
            $this->_array = $array;
        }
        
        function __get($key)
        {
            return $this->get($key);
        }
        
        function __set($key, $value)
        {
            $this->set($key, $value);
        }
        
        function count()
        {
            return count($this->_array);
        }
        
        function set($key, $value)
        {
            if (eregi("\.", $key))
            {
                $expl  = explode(".", $key);

                $this->__SetRecursiveArray($expl, 0, $this->_array, $value);
            } else {
                $this->_array[$key] = $value;
            }
        }
        function __SetRecursiveArray($keys, $i, &$array, $dato)
        {
            if ($i < count($keys))
            {
                $this->__SetRecursiveArray($keys, $i + 1, $array[$keys[$i]], $dato);
            } else {
                $array = $dato;
            }
        }
        function get($key)
        {
            if (eregi("\.", $key))
            {
                $expl  = explode(".", $key);

                return $this->__GetRecursiveArray($expl, 0, $this->_array);
            }
            return $this->_array[$key];
        }
        function __GetRecursiveArray($keys, $i, $sess)
        {
            if ($i < count($keys))
            {
                return $this->__GetRecursiveArray($keys, $i + 1, $sess[$keys[$i]]);
            }
            return $sess;
        }
        function toArray()
        {
            return $this->_array;
        }
        function intval($key)
        {
            $temp = intval($this->_array[$key]);
            if ($this->_array[$key] != '0' && !empty($this->_array[$key]) && $temp == 0)
            {
                Error::NotIntVal($key);
            }
            return $temp;
        }
        function getArrayFromKey($key)
        {
            $array = array();
            foreach ($this->_array as $value)
            {
                $array[] = $value[$key];
            }
            return $array;
        }
        function is_set($key)
        {
            if (eregi("\.", $key))
            {
                $expl  = explode(".", $key);
                $array = $this->_array;
                
                foreach ($expl as $k)
                {
                    if (!is_array($array))
                    {
                        break;
                    }
                    if (isset($array[$k]))
                    {
                        $array = $array[$k];
                    } else { 
                        return false;
                    }
                }
                return true;            
            } else {
                return isset($this->_array[$key]);
            }
        }
        
        function toString()
        {
            return "[ArrayHelper Object]";
        }
    }

?>