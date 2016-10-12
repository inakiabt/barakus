<?php
    LibManager::import('Upload.class\.upload');
    class UploadHelper
    {
        private $uploader = null;
        private $path = null;
        
        private $log = null;
        private $error = null;
        private $file_max_size = null;
        private $file_overwrite = null;
        private $file_safe_name = null;
        private $file_auto_rename = null;
        private $file_new_name_body = null;
        private $file_name_body_add = null;
        
        public function __construct($file, $lang = 'es_ES')
        {
            $this->uploader = new upload($file, $lang);
            $this->uploader->file_safe_name = false;
        }
        public function upload($name = '', $path = '')
        {
            $this->path = $path;
            if (!empty($name))
            {
                $this->newBodyName($name);
            }
            
            $this->setPropertie('file_overwrite', $this->file_overwrite);
            $this->setPropertie('file_safe_name', $this->file_safe_name);
            $this->setPropertie('file_auto_rename', $this->file_auto_rename);
            $this->setPropertie('file_new_name_body', $this->file_new_name_body);
            $this->setPropertie('file_max_size', $this->file_max_size);
            $this->setPropertie('file_name_body_add', $this->file_name_body_add);
            
            $this->uploader->process($this->path);
            
            return $this->uploader->processed;
        }
        public function error()
        {
            return $this->uploader->error;
        }
        public function setError($error)
        {
            $this->uploader->error = $error;
        }
        public function log()
        {
            return $this->uploader->log;
        }
        public function overwrite($bool)
        {
            $this->file_overwrite = $bool;
        }
        public function safeName($bool)
        {
            $this->file_safe_name = $bool;
        }
        public function autoRename($bool)
        {
            $this->file_auto_rename = $bool;
        }
        public function newBodyName($name)
        {
            $this->file_new_name_body = $name;
        }
        public function newBodyAppend($name)
        {
            $this->file_name_body_add = $name;
        }
        public function maxSize($size)
        {
            $this->file_max_size = $size;
        }
        private function setPropertie($name, $value)
        {
            if (!is_null($value))
            {
                $this->uploader->{$name} = $value;
            }
        }
        public function getUploader()
        {
            return $this->uploader;
        }
    }
?>
