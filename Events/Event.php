<?php

class Event
{
	var $type = 'click';
	var $id   = '';
    var $htmlid = '';
    var $params;
    var $page;
    var $postBody = "";

	function Event($call, $params = array(), $pre_post = array())
	{
        $this->page   = Barakus::getPage();
        $this->call   = $call;
        $this->params = $params;
        $this->pre    = $pre_post['pre'];
        $this->post   = $pre_post['post'];
	}
    
    function setPage($page)
    {
        $this->page = $page;
    }
    
    function getPage()
    {
        return $this->page;
    }
    
    function getPostBody()
    {
        return 'page='.$this->page.'&action='.$this->call.'&ajaxCall[MD5]=' . md5($this->page) . $this->postBody;
    }
    
    function addVar($var, $value)
    {
        $this->postBody .= '&' . $this->urlVar($var) . '=' . $value;
    }
    
    function urlVar($var)
    {
        return 'ajaxCall['.$var.']';
    }

	function showSource()
	{
        $this->postBody = $this->getPostBody();
        
		LibManager::import("Ajax.JSON");
		$JSON = new Services_JSON();
		$keys = $array = array();
		$setup = '';
		$postBody = $this->postBody;
		if (count($this->params))
		{                                                     
            $i = 0;
			foreach($this->params as $key => $param)
			{                             
                if (is_array($param))
                {
                    $keys  = array_keys($param);
                    $name  = $keys[0];
                    $param = $param[$name]; 
                    $this->params[$i] = $param;
                } else {
                    if (is_string($key))
                    {
                        $name = $key;
                    } else {
                        $name  = $param;
                    }                    
                }
                
                $param = str_replace('.', '_', $param);
				$keys[] = 'param' . $i;
				$setup .= 'param'. $i .": '" . $param . "',\r\n";
                $exp = explode('@', $this->params[$i]);
                if (count($exp)>1)
                {
                    $mtd = $exp[1];
                } else {
                    $mtd = 'getText()';
                }
                
                if ($this->params[$i][0] == '.')
                {
                    $postBody .= '&'.$this->urlVar('value').'=" + Event.element(event).'.$mtd.' + "';
                } else {
				    $postBody .= '&'.$this->urlVar($name).'=" + $(\''.$param.'\').'.$mtd.' + "';
                }
                $i++;
			}
		}
		$ret = "var CallBacks_".$this->id." = {
		".$setup."
	doCallBack: function(event)
	{
        var doRequest = true;
";
    if (!empty($this->pre)) 
    {
        $ret .= "   doRequest = ".$this->pre."('{$this->id}');
";
    }                        
$ret .= "       
        if (doRequest)
        {         
            var loader = $('".$this->id."_loader');
            if (loader)
            {
                loader.style.display = 'inline';
            }
		    var options = {
			    method: 'post',
			    postBody: \"".$postBody."\",
			    onSuccess: function(transport, json)
			    {
				    if (!json)
				    {
					    //alert(transport.responseText);
                        ";
                        if (!empty($this->post)) 
                        {
                            $ret .= $this->post."(transport, '{$this->id}');
                    ";
                        }                        
                        $ret .= "
				    } else {
					    Barakus.catchCallBack(json);
    ";
                        if (!empty($this->post)) 
                        {
                            $ret .= $this->post."(transport, '{$this->id}');
                    ";
                        }                        
                        $ret .= "
				    }
                    if (loader)
                    {
                        loader.style.display = 'none';
                    }
			    }
		    }
            new Ajax.Request('".Server::get('PHP_SELF')."', options);
            if (event)
            {
                Event.stop(event);
            }
            return false;
        }
	}
}
Event.observe(window, 'load', function() 
{
";
    if ($this->htmlid[0] == '.')
    {
    $ret .= "
        \$\$('{$this->htmlid}').each(function(e) {
            Event.observe(e, '".$this->type."', CallBacks_".$this->id.".doCallBack.bindAsEventListener(this));
        });
    ";
    } else {
    $ret .= "
        if (\$('".$this->id."'))
        {
            Event.observe('".$this->id."', '".$this->type."', CallBacks_".$this->id.".doCallBack.bindAsEventListener(this));
        }
    ";
    }
        return $ret."});\r\n";
	}
}

?>