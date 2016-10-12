<?php
	class CallBackEventFactory
	{
		function get($type, $updater, $params, $pre_post = array())
		{
			switch ($type)
			{
				case 'click': 
                    Barakus::import('Barakus.Events.ClickCallBackEvent');
                    return new ClickCallBackEvent($updater, $params, $pre_post);
			}
		}
	}
?>