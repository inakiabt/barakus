var Select = {
    load: function()
    {
    },
    fill: function(params)
    {
        var elem_id = params[0];
        var array   = params[1];
        var config  = params[2];
        
        var elem = $(elem_id);
        elem.options.length=0 ;
        
        if (array == '')
        {
            elem.disabled = true;
            return;
        }
        var total = array.length;
        if (total > 0)
        {
            elem.disabled = false;
            if (config.emptySelection)
            {
                var option = Builder.node('option', {value: ''}, 'Seleccione');
                option.selected = true;
                elem.appendChild(option);    
            }
            var text;
            var id;
            for (var i = 0; i < total; i++)
            {
                eval('text = array[i].' + config.text + ';');
                eval('id   = array[i].' + config.id + ';');
                if (text == '') continue;
                var option = Builder.node('option', {value: id}, text);
                option.selected = false;
                elem.appendChild(option);    
            }
            elem.options[0].selected = true;
        }
    },
    select: function(params)
    {
        var elem     = $(params[0]);
        var selected = params[1];
        
        var total = elem.options.length;
        for (var i = 0; i < total; i++)
        {
            if (elem.options[i].value == selected)
            {
                elem.options[i].selected = true;
            } else {
                elem.options[i].selected = false;
            }
        }
    }
}
var Barakus = { 
    Select: Select,
    load: function(js) 
    { 
        if ($(js)) 
        { 
             return; 
        } 
        var head = document.getElementsByTagName("head")[0]; 
        script = document.createElement('script'); 
        script.id = js; 
        script.type = 'text/javascript'; 
        script.src = js; 
        head.appendChild(script) 
    }, 
    execute: function(item) 
    { 
        var typeFunction = eval("typeof(" + item[0] + ") == 'function'") || item[0] == 'alert'; 

        if (item[0] != "" && typeFunction) 
        { 
            var param;
            if (typeof(item[1]) != 'object')
            {
                param = '"' + item[1] + '"';
            } else {
                param = 'item[1]';
            }
            eval(item[0] + '(' + param + ');'); 
        } 
    }, 

    catchCallBack: function(JSONobject) 
    { 
        for (var i=0, logic; logic = JSONobject[i]; i++) 
        { 
            Barakus.execute(logic); 
        } 
    } 
}; 

var Tools = { 
    getText: function(element) 
    { 
        if (!element) 
        { 
            return 'undefined'; 
        } 
        if (element.value && element.value != 'undefined') 
        { 
            return element.value; 
        } 
        if (element.innerHTML && element.innerHTML != 'undefined') 
        { 
            return element.innerHTML; 
        } 
        return 'undefined'; 
    }, 
    setText: function(element, value) 
    { 
        if (!element) 
        { 
            return; 
        } 
        if (element.value && element.value != 'undefined' || element.value == '') 
        { 
            element.value = value; 
        } else if (element.innerHTML && element.innerHTML != 'undefined' || element.innerHTML == '') 
        { 
            element.innerHTML = value; 
        } 
        return; 
    }     
} 
Element.addMethods(Tools);
function $RF(name) 
{
    var chked = $$('input[type=radio][name=' + name + ']').find(function(el) { return el.checked });
    if (chked)
    {
        return chked.value;
    }
    return null;
}                      
