<?php
function smarty_function_autocomplete($attrs, $smarty)
{
    $smarty->addJs('prototype');
    $smarty->addJs('scriptaculous');
    $smarty->addJs('effects');
    $smarty->addJs('controls');
    $smarty->addJs('Barakus');
    $smarty->addJs('barakus.ajaxAutocompleter');
    if (empty($attrs['field']))
    {
        $smarty->trigger_error("autcomplete: field could not be empty");
    }    
    if (empty($attrs['action']))
    {
        $smarty->trigger_error("autcomplete: action could not be empty");
    }    
    if (empty($attrs['id']))
    {
        $attrs['id'] = 'autocomplete_choices_' . $attrs['field'];
    }    
    if (empty($attrs['class']))
    {
        $attrs['class'] = 'autocomplete';
    }    
    if (empty($attrs['paramName']))
    {
        $attrs['paramName'] = $attrs['field'];
    }    
    
    $field = $attrs['field'];
    Barakus::import('Barakus.Events.Event');
    //$attrs['paramName'] = Event::urlVar($attrs['paramName']);
    
    $autocomplete = '<div id="'.$attrs['id'].'" class="'.$attrs['class'].'"></div>';
    if (!empty($attrs['loader']))
    {
        $indicator = $attrs['field'].'_loader';
        $attrs['indicator'] = $indicator;
        $loader       = '<img src="'.$attrs['loader'].'" id="'.$indicator.'" style="display: none" '.(empty($attrs['loader_class']) ? '' : 'class="'.$attrs['loader_class'].'"').'/>';
    }

    $action = $attrs['action'];
    foreach ($attrs as $var => $value)
    {
        switch($var)
        {                    
            case 'class':
            case 'loader':
            case 'loader_class':
                unset($attrs[$var]);
        }
    }
    $event = EventManager::getEventInstance('AutoCompleteCallBackEvent', $action, $attrs);
    if (!empty($attrs['page']))
    {
        $event->page = $attrs['page'];
    }
    $smarty->addEvent(ViewHelper::getId(array('name' => $field)), $event);
    return $loader . $autocomplete;
}
?>
