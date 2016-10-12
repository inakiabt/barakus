<?php

class RequestSender
{
	var $logic = array();
    var $__auxvars = array();    

	function RequestSender()
	{
		$req = Post::get('ajaxCall');
		if (count($req))
		{
			foreach($req as $name => $value)
			{
				if ($name == 'MD5')
				{
					continue;
				}
                $this->$name       = $this->getElement($name);
				$this->$name->text = $value;
                $this->__auxvars[$name] = $value;
			}
		}
	}
    function createElement($name)
    {
        if (!isset($this->$name))
        {
            $this->$name = $this->getElement($name);
            $this->$name->text = '';
            $this->__auxvars[$name] = '';
        }
        return $this->$name;
    }
	function update($raw_data = false)
	{
        if ($raw_data === false)
        {
            foreach ($this->__auxvars as $name => $value)
            {
                if (!empty($this->$name) && $this->$name->text == $value)
                {
                    unset($this->$name->text);
                }
            }
		    $this->__newLogic('eval', addslashes($this->__updateParams()));
		    $this->__send();
        } else {
            $AJAX = LibManager::get("Ajax.Ajax");
            $AJAX->send($raw_data);
        }
	}
    
	function __updateParams($obj = -1, $id = '')
	{
		if ($obj == -1)
		{
			$obj = $this;
		}
		$jsGenCode = '';
		$params = get_object_vars($obj);
		if (count($params))
		{
			foreach($params as $name => $Obj)
			{
				if ($name == 'logic' || $name == '__auxvars' || $name == '__sender' || $name == '__element')
				{
					continue;
				}
				if (is_object($Obj))
				{
					$jsGenCode .= $this->__updateParams($Obj, $name);
				} else {
					$jsGenCode .= $this->__getJsCode($id, $name);
				}
			}
		}

		return $jsGenCode;
	}

    function __send()
    {
        LibManager::get("Ajax.JSON");
        $JSON = new Services_JSON();
        $AJAX = LibManager::get("Ajax.Ajax");
        $AJAX->sendJSON($JSON->encode($this->logic));
    }

    function sendJson($array)
    {
        LibManager::get("Ajax.JSON");
        $JSON = new Services_JSON();
        $AJAX = LibManager::get("Ajax.Ajax");
        $AJAX->sendJSON($JSON->encode($array));
    }

	function __newLogic($type, $logic)
	{
		if (empty($logic))
		{   
			return;
		}

        $this->logic[] = array($type, $logic);
	}
    
	function __getJsCode($id, $name)
	{
		$jsCode = '$(\''.$id.'\')';

		if ($name == 'value' || $name == 'innerHTML')
		{
			$param = 'text';
		} else {
			$param = $name;
		}

        if (is_object($name) && get_class($name) == 'SenderElementObject')
        {
        } elseif ($name == 'text')
		{
			$jsCode .= '.setText(\''.$this->$id->$param.'\')';
		} else if ($name == 'style')
		{
			$styleCode = $jsCode;
			$jsCode = '';
			$jsCodeArray = array();
            if (substr($this->$id->$param, -1) != ';')
            {
                $this->$id->$param .= ';';
            }
			$expl = explode(';', $this->$id->$param);
			array_pop($expl);
			foreach($expl as $p => $set)
			{
				$q = explode(':', trim($set));
				if (eregi('-', $q[0]))
				{
					$e = explode('-', $q[0]);
					$set = $e[0] . ucfirst($e[1]);
				} else {
					$set = $q[0];
				}
				$jsCodeArray[] = $styleCode.'.' . $param . '.'.$set.' = \''.trim($q[1]).'\'';
			}
			$jsCode .= implode(';', $jsCodeArray);
		} else {
			$jsCode .= '.' . $param . ' = \''.$this->$id->$param.'\'';
		}

		return $jsCode . ';';
	}
    
    function onUpdate($function, $param)
    {
        $this->__newLogic($function, $param);
    }

	function alert($txt)
	{
		$this->__newLogic('alert', $txt);
	}
    
    function getElement($name)
    {
        return new SenderElementObject($name, $this);
    }

}

class SenderElementObject
{
    var $__sender;
    var $__element;
    function SenderElementObject($element, $sender)
    {
        $this->__sender  = $sender;
        $this->__element = $element;
    }
    function __call($method, $params)
    {
        $this->__sender->onUpdate('eval', '$(\''.$this->__element.'\').' . $method . '(' . implode(', ', $params) . ');');
    }
}
?>