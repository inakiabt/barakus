<?php
    class RenderedView
    {
        var $view = null;
        var $template = "";
        
        function RenderedView(&$view)
        {
            if (!is_null($view))
            {
                $this->view = &$view;
            }        

        }
        
        function get($var)
        {
            return $this->view->get_template_vars($var);
        }
        
        function render()
        {
            die("TODO: es necesario sobreescribir el metodo 'render'");
        }      
        
        function model($model = '')
        {
            $this->$model = Model::getModel($model);
        }
        
        function getModel($model = '')
        {
            if (empty($model))
            {
                Error::ModelEmpty();
            }

            Barakus::import(Config::get("model_dir_real") . "." . $model);
            $class = $model.'Model';
            return new $class;
        }                                                   
        
        function view($view)
        {
            $this->template = $view;
        }
        
        function show()
        {
            if (!empty($this->template))
            {
                return $this->view->fetch(Barakus::getPath(Config::get("views_dir_real") . "." . $this->template) . "." . Config::get('view_extension'));
            }
            return '';
        }
        function end()
        {
            if (is_object($this->view->controller) && $this->view->controller->loaded)
            {
                $this->view->controller->end();
            }
        }
        function getRenderable($renderable)
        {
            $class = "R{$renderable}View";
            if (Barakus::import(Config::get("views_dir_real") . ".Renderable." . $renderable, true))
            {
                $expl = explode('.', $renderable);
                $class = "R".array_pop($expl)."View";
                return new $class(new View());
            }
            return null;
        }
    }
?>
