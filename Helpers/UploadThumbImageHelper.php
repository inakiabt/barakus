<?php
    Barakus::import('Barakus.Helpers.ThumbImageHelper');
    class UploadThumbImageHelper extends ThumbImageHelper
    {
        private $uploader = null;
        private $error = '';
        
        public function __construct($uploader)
        {
            $this->uploader = $uploader;
        }
        
        public function resize()
        {
            parent::resize();
            
            $error = $this->error();
            if (empty($error))
            {
                $this->uploader->getUploader()->image_resize = true;
                $this->uploader->getUploader()->image_ratio = true;
                $this->uploader->getUploader()->image_ratio_no_zoom_out = true;
                if (!is_null($this->getWidth()))
                {
                    $this->uploader->getUploader()->image_ratio_y = true;
                    $this->uploader->getUploader()->image_x = $this->getWidth();
                }
                if (!is_null($this->getHeight()))
                {
                    $this->uploader->getUploader()->image_ratio_x = true;
                    $this->uploader->getUploader()->image_y = $this->getHeight();
                }
                
                $this->uploader->upload('', $this->getPath());
                
                $this->setError($this->uploader->error());
                
            }
            
            return $this;
        }
        
    }
?>
