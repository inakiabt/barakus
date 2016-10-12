            var ultimo  = null;
            var marcado = 'traza_0';
            var cache   = new Array();
            function AbrirArchivo(file, line, div)
            {

                if (ultimo != null)
                {
                    DesMarcar(ultimo);
                }
                ultimo = $(div);
                if (cache && cache[div] && cache[div] != '')
                {
                    marcado = div;
                    Marcar(div);
                    $('archivo').innerHTML = cache[div];
                    return;
                }
                var opt = {
                    // Use POST
                    method: 'post',
                    // Send this lovely data
                    postBody: 'file=' + file + '&line=' + line,
                    // Handle successful response
                    onSuccess: function(t) {
                        marcado = div;
                        Marcar(div);
                        $('archivo').innerHTML = t.responseText;
                        cache[div] = t.responseText;
                    },
                    // Handle 404
                    on404: function(t) {
                        alert('Error 404: location \"' + t.statusText + '\" was not found.');
                    },
                    // Handle other errors
                    onFailure: function(t) {
                        alert('Error ' + t.status + ' -- ' + t.statusText);
                    }
                }
                var a = new Ajax.Request('?BARAKUS_ERROR=ShowSource', opt);
            }
            function Marcar(div)
            {
                $(div).style.border = '1px solid #C64A00';
                $(div).style.backgroundColor = '#FFDECA';
            }
            function DesMarcar(div)
            {
                if (div.id != marcado)
                {
                    div.style.border = '1px solid #FFF7F2';
                    div.style.backgroundColor = '#FFF7F2';
                }
            }
