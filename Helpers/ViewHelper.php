<?php
class ViewHelper
{
    function createList($array, $return = false)
    {
        $content = '';
        $content .= "<ul>\n";
        foreach ($array as $index => $value)
        {
            $content .= "\t<li>$value</li>\n";
        }
        $content .= "</ul>\n";
        
        if ($return)
        {
            return $content;
        }
        echo $content;
    }
    function _getHTMLComponentName($name)
    {
        if (ereg('\.', $name))
        {
            $expl = explode(".", $name);
            $value = $expl[0];
            array_shift($expl);
            foreach ($expl as $index)
            {
                $value .= "[$index]";
            }
            return $value;
        }
            
        return $name;
    }
    function _getStateValue($state, $name, $check = true)
    {            
        if (ereg('\.', $name))
        {
            $expl = explode(".", $name);
            $value = $state;
            foreach ($expl as $index)
            {
                if (empty($index))
                {
                    continue;
                }
                $value = $value[$index];
            }
            $return = $value;
        } else {
            $return = $state[$name];
        }
        if ($check && is_array($return))
        {
            if (substr($name, -1) == '.')
            {
                $global_value = intval($GLOBALS['__'.$name]); 
                $return_value  = $return[$global_value];
                
                $GLOBALS['__'.$name] = $global_value + 1;
            } elseif (count($expl)) {
                $index         = array_pop($expl);
                $return_value  = $return[$index];
            }
            
            return $return_value;
        }
        return $return;
    }
    
    function input($params)
    {
        $params['type'] = 'text';
        ViewHelper::create_element('input', $params);
    }
    
    function hidden($params)
    {
        $params['type'] = 'hidden';
        ViewHelper::create_element('input', $params);
    }
    
    function input_file($params)
    {
        $params['type'] = 'file';
        ViewHelper::create_element('input', $params);
    }
    
    function textarea($params)
    {
        ViewHelper::create_element('textarea', $params);
    }
    
    function radio($params)
    {
        $params['type'] = 'radio';
        ViewHelper::create_element('input', $params);
    }
    
    function checkbox($params)
    {
        $params['type'] = 'checkbox';
        ViewHelper::create_element('input', $params);
    }

    function password($params)
    {
        $params['type'] = 'password';
        ViewHelper::create_element('input', $params);
    }
    
    function button($params)
    {
        $params['type'] = 'button';
        ViewHelper::create_element('input', $params);
    }
    
    function button_submit($params)
    {
        $params['type'] = 'submit';
        ViewHelper::create_element('input', $params);
    }
    
    function select($params)
    {
        return ViewHelper::create_element('select', $params, true);
    }   
    
    function option($params)
    {
        unset($params['name'], $params['sel']);
        return ViewHelper::create_element('option', $params, true, $params['short_cut']);
    }   
    
    function link($params)
    {
        return ViewHelper::create_element('a', $params, true);
    }   
    
    function img($params)
    {
        return ViewHelper::create_element('img', $params, true);
    }   
    
    function getId($parms)
    {
        if (empty($parms['id']))
        {
            return str_replace('.', '_', $parms['name']);
        }
        return $parms['id'];
    }
    
    function create_element($field,$parms,$return = false, $short_cut = false)
    {
        unset($parms['short_cut']);

        if (!empty($parms['name']))
        {
            $name          = $parms['name'];
            $parms['id']   = ViewHelper::getId($parms);
            $parms['name'] = ViewHelper::_getHTMLComponentName($parms['name']);
        }
        $fieldTag = array();
        $tag = '';

        $fieldTag[] = "<$field";

        foreach ($parms as $key => $val)
        {
            $nval = str_replace('"', "&quot;", $val);
            $nval = preg_replace('|´&quot;(.+)&quot;´|', '´"$1"´', $nval);
            //$nval = str_replace('´&quot;', '"', $val);
            //$nval = str_replace('&quot;´', '"', $val);
            $fieldTag[] = "$key=\"".$nval."\"";
        }

        $tag .= implode(' ',$fieldTag);

        if ($field == "input") 
        {
                $tag .= " /";
        }

        $tag .= ">";
        if (substr($name, -1) == '.')
        {
            $name = substr($name, 0, -1);
        }
        if ($short_cut && !empty($name))
        {
            $tag = '<a name="error-'.$name.'"></a>'.$tag;
        }
        if ($return)
        {
            return $tag;
        }
        print $tag;
    }
}
?>
