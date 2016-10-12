<?php

class URLMap
{
	function getURL($url)
	{             
		if (Config::get('tiny_url'))
		{	      
			$config = Config::get('tiny_url_rules');

			$expl = explode('?', $url);
			$parseable_url = $expl[1];
			parse_str($parseable_url, $url_vars);
			$aux_url = $url_vars;
			unset($aux_url['page']);
                                    
			$new_url = basename(Server::get('SCRIPT_NAME')) . '?page=' . $url_vars['page'] . (empty($aux_url['action']) ? '' : '&action=' . $aux_url['action']);
            
            unset($aux_url['action']);

			$i = 1;
			foreach($aux_url as $name => $value)
			{
				$new_url .= '&' . $name . '=$' . $i;
                $i++;
			}               
            
			$rule = $config[md5($new_url)];     
			if (!empty($rule))
			{			
				$url = substr($rule['original_pattern'], 0, -1);
				foreach($aux_url as $name => $value)
				{
					$url = str_replace('{'.$name.'}', $value, $url);
				}
			} else {
                return DOCUMENT_APP . '/' . $new_url;
            }
		}

		return $url;
	}
}

?>