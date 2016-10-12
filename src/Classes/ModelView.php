<?php
    class ModelView
    {
        function __get($var)
        {
            if (!is_object($this->$var))
            {
                $this->$var = Model::getModel($var);
            }
            return $this->$var;
        }
    }
?>
