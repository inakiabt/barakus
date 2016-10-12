<?php
Barakus::import("Barakus.Events.Event");
class FunctionCallBackEvent extends Event
{
    function showSource()
    {
        LibManager::get("Ajax.JSON");
        $keys = $array = array();
        $setup = '';
        $postBody = $this->postBody;
        if (count($this->params))
        {
            foreach($this->params as $i => $param)
            {
                $keys[] = 'param' . $i;
                $setup .= 'param'. $i .": '" . $param . "',\r\n";
                $postBody .= '&'.$this->urlVar($param).'=" + $(\''.$param.'\').getText() + "';
            }
        }
        return "function ".$this->id."() {
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
                    alert(transport.responseText);
                } else {
                    if (loader)
                    {
                        loader.style.display = 'none';
                    }
                    Barakus.catchCallBack(json);
                }
            }
        }
        new Ajax.Request('".Server::get('PHP_SELF')."', options);
}\r\n";
    }    
}

?>