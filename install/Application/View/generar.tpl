{include file="header.tpl"}
<script type="text/javascript">
{literal}
	var keys = new Array();
	var actual = 0;
	var HayError = false;
	var Estado;
	var omitir = false;
	function Iniciar()
	{
		var gen;
		//var json;
        var opt = {
            method:    'post',
            postBody:  'dir={/literal}{$path}{literal}',
            onSuccess: function(t, archivos) {
            	json = archivos;
				if (json.error && json.error != '')
				{
					HayError = true;
					$('estado').innerHTML = json.error + ' - Halt!';
					return;
				}				
            	k = 0;
            	for (var i in json)
            	{
            		keys[k] = i;
            		k++;
            	}
          	},
          	onComplete: function(r, json){
				if (!HayError)
				{
					Complete(json, 'P_0');
				}
          	},
            on404: function(t) {
                alert('Archivo no existe');
            },
            onFailure: function(t) {
                alert('Error al enviar datos');
            }
        }
    	new Ajax.Request('?page=Generar&action=GetFiles', opt);
	}
	function Complete(json, i)
	{
		if (json[i])
		{
	    	$('estado').innerHTML = 'Generando <b>' + json[i].nombre + '<b>...';
			Generar(json, json[i], i, 0);
		} else {
       		$('estado').innerHTML = '<b>Proceso finalizado' + siHayErrorMostrar() + '!</b>';
		}
	}
	function siHayErrorMostrar()
	{
		if (HayError)
		{
			return ' con Errores';
		}
		return '';
	}
	function Generar(JSON, response, id, sob)
	{
		if (!sob)
		{
			var texto = getTipo(response.type) + " " + response.nombre + ": ";
			var Div = Builder.node("div", {id: id,  className: "bold"}, [texto]);
			$('resultado').appendChild(Div);
		}
		var div = $(id);
        var opt = {
            method:    'post',
            postBody:  'dir=' + id + '&sob=' + sob,
            onSuccess: function(t, json) {
            	if (t.responseText != '')
            		alert(t.responseText);
            		//document.write(t.responseText);
            	if (!json)
            	{
            		alert("error");
            		return;
            	}
				if (json.error && json.error != '')
				{
					var div2 = Builder.node("div", {className: "red", style: "display: inline"}, [json.error]);
					HayError = true;
				} else if (json.halt) {
					if (!omitirTodos())
					{
						Estado = {div: div, json: JSON, response: response, id: id};
						doConfirm(json.msg, 1);
						return;
					} else {
						var div2 = Omitido();
					}
				} else {
					var div2 = Builder.node("div", {className: "green", style: "display: inline"}, ["OK!"]);
				}
				div.appendChild(div2);
				actual++;
				Complete(JSON, keys[actual]);
          	},
            on404: function(t) {
                alert('Archivo no existe');
            },
            onFailure: function(t) {
                alert('Error al enviar datos');
            }
        }
    	new Ajax.Request('?page=Generar&action=Create', opt);
	}
	function doConfirm(msg, ask)
	{
		GeneratePopup({msg: msg, ask: ask});
	}
	function getTipo(tipo)
	{
		if (tipo == 'dir')
		{
			return 'Directorio';
		} else {
			return 'Descargando Archivo';
		}
	}
	function respuestaOk()
	{
		Generar(Estado.json, Estado.response, Estado.id, 1);
		omitir = chkOmitirRestantes();
		
		OcultarPopup();
	}
	function chkOmitirRestantes()
	{
		return $('chkOmitir').checked;
	}
	function respuestaCancel()
	{
		omitir = chkOmitirRestantes();
		var div2 = Omitido();
		
		Estado.div.appendChild(div2);
		actual++;
		Complete(Estado.json, keys[actual]);
		OcultarPopup();
	}
	function Omitido()
	{
		return Builder.node("div", {className: "green", style: "display: inline"}, ["Omitido"]);
	}
	function OcultarPopup()
	{
		//$('popup').style.display = 'none';
		new Effect.BlindUp('popup');
	}
	function GeneratePopup(params)
	{
		var div = $('popup');
		var contenedor = Builder.node("div");
		if (params.ask)
		{
			var pregunta   = Builder.node("span", {id: 'mensaje'}, [params.msg]);
			var divBotones = Builder.node("div", {id: "botones2"});
			var botonOk    = Builder.node("input", {type: "button", className: "boton", value: "Aceptar"});
			var botonCancel= Builder.node("input", {type: "button", className: "boton", value: "Cancelar"});
			var chkCont    = Builder.node("div");
			var checkbox   = Builder.node("input", {type: "checkbox", id: "chkOmitir", value: "Cancelar"});
			chkCont.appendChild(checkbox);
			chkCont.appendChild(Builder.node("span", [" Omitir los restantes"]));
			
			Event.observe(botonOk,     "click", respuestaOk);   
			Event.observe(botonCancel, "click", respuestaCancel);   
			
			divBotones.appendChild(botonOk);
			divBotones.appendChild(botonCancel);
			
			contenedor.appendChild(pregunta);
			contenedor.appendChild(divBotones);
			contenedor.appendChild(chkCont);
		}
		
		div.removeChild(div.firstChild);
		div.appendChild(contenedor);
		
		new Effect.BlindDown('popup');
	}	
	function omitirTodos()
	{
		return omitir;
	}
{/literal}
</script>
<div style="text-align: center;">
<img src="img/logo.jpg" />
</div>
<h2>Generar estructura en path: {$path}</h2>
<div style="text-align: center;">
	<div id="popup" style="display: none">
	</div>
</div>
<br />
<div id="estado" style="background-color: #FFF895; border: 1px solid red; width: 30%; padding: 10px;">
Iniciando...
<script type="text/javascript">
	Iniciar();
</script>
</div>
<div id="resultado" style="margin-top: 15px;">
</div>
{include file="footer.tpl"}