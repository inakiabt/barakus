<?php
Barakus::import("Barakus.Events.Event");
class AutoCompleteCallBackEvent extends Event
{
	function showSource()
    {
        $this->params['method'] = 'post';
        $this->params['postBody'] = $this->getPostBody();

        LibManager::import("Ajax.JSON");
        $json = new Services_JSON();
        
        $json_encode = $json->encode($this->params);
        
        $this->_sanitizeJavascriptFunction($json_encode, 'afterUpdateElement');
        $this->_sanitizeJavascriptFunction($json_encode, 'updateElement');
        $this->_sanitizeJavascriptFunction($json_encode, 'callback');
        $this->_sanitizeJavascriptFunction($json_encode, 'onComplete');
        $this->_sanitizeJavascriptFunction($json_encode, 'onFinish');
        
        return "\r\nEvent.observe(window, 'load', function() 
{
    ".'new Barakus.AjaxAutocompleter("'.$this->params['field'].'", "'.$this->params['id'].'", "'.Server::get('PHP_SELF').'", '.$json_encode.');'."
});\r\n";
    }
    function _sanitizeJavascriptFunction(&$json_encode, $function)
    {
        if (isset($this->params[$function]))
        {
            $json_encode = str_replace('"'.$function.'":"'.$this->params[$function].'",', '"'.$function.'":'.$this->params[$function].',', $json_encode);
        }
    }
}

?>