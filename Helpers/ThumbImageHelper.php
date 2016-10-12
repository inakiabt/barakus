<?php
    abstract class ThumbImageHelper
    {
        private $width = null;
        private $heigt = null;
        private $path = null;
        
        private $error = null;

        public function resize()
        {
            if (is_null($this->path))
            {
                $this->error = '"'.$this->path.'" Path no existe';
            } elseif (is_null($this->width) && is_null($this->heigt))
            {
                $this->error = 'Width y Height no seteados';
            }
            return $this;
        }

        public function process($path)
        {
            $this->path = $path;
            $this->heigth = null;
            $this->width = null;
            return $this;
        }
        public function width($width)
        {
            $this->width = $width;
            return $this;
        }        
        public function height($height)
        {
            $this->heigt = $height;
            return $this;
        }        
        
        public function setWidth($width)
        {
            $this->width = $width;
        }
        
        public function getWidth()
        {
            return $this->width;
        }
        
        public function setHeight($height)
        {
            $this->height = $height;
        }
        
        public function getHeight()
        {
            return $this->height;
        }
        
        public function setError($error)
        {
            $this->error = $error;
        }
        
        public function getPath()
        {
            return $this->path;
        }
        
        public function error()
        {
            return $this->error;
        }
        
    }
?>