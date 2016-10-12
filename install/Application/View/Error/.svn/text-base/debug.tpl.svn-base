{if !$source_only}
<?xml version="1.0" encoding="iso-8859-1"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
<head>
	<title>Barakus - Error</title>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<script type="text/javascript" src="{$DOCUMENT_APP}/js/prototype.js"></script>
	<script type="text/javascript" src="{$DOCUMENT_APP}/js/scriptaculous.js"></script>
	<script type="text/javascript">
	{literal}
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
	            var a = new Ajax.Request('?error=ShowSource', opt);
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
	{/literal}
	</script>
	<style type="text/css">
		{literal}
		A {
			color: #000;
			text-decoration: none;
		}
		A:hover {
			text-decoration: underline;
		}

		div.trace {
			font-size: 10px;
			font-family: Verdana;
			padding: 2px 0 2px 2px;
			margin-bottom: 2px;
			color: #666;
			border: 1px solid #FFF;
		}

		div#trazas {
			background-color: #FFF7F2;
			border: 1px solid #C64A00;
			padding: 5px;
		}
		div#backtrace {
			margin-top: 5px;
			background-color: #FFC49F;
			border: 1px solid #C64A00;
			padding: 5px;
			font-family: Verdana;
			font-size: 14px;
			font-weight: bold;
			border-bottom: none;
		}
		{/literal}
	</style>
</head>
<body>
	<h1 style="font-family: Verdana; margin: 0px 0 5px 0; padding: 0; border: 1px solid #F00; background-color: #FFF4F4">{$type}</h1>
	<div id="archivo" style="font-family: Verdana; font-size: 12px;">
{/if}
		<b><a name="top">Source File</a>:</b> {$file} (line {$line})
		<table width="100%" cellspacing="0" cellpadding="0" style="font-size: 12px; margin-top: 4px; border: 1px solid #484800;">
			{foreach from=$lineas key=numero item=linea }
			<tr>
					<td style="vertical-align: top; text-align: center; padding: 1px; {if $numero == $line}border: 1px solid #F00; border-right: none; border-left: none; background-color: #FFD7D7; color: red; {else} border-right: 1px solid #484800;background-color: #FFFFC4; color: #8A8A00; {/if}">
						<code>
						{$numero}
						</code>
					</td>
					<td style="vertical-align: top; {if $numero != $line}background-color: #FFFFF4;{else}border: 1px solid #F00; border-left: none; border-right: none; background-color: #FFD7D7;{/if}">
						<code>
							&nbsp;{$linea}
						</code>
					</td>
	    	</tr>
			{/foreach}
		</table>
{if !$source_only}
	</div>
<div id="backtrace">Backtrace</div>
<div id="trazas">
	<div class="trace" id="traza_0" onmouseover="Marcar('traza_0')" onmouseout="DesMarcar(this);">
		0: <a href="#top" onclick="AbrirArchivo('\{$error.file}', '{$error.line}', 'traza_0');">{$error.file_strip} (line {$error.line})</a>: {$error.msg}
	</div>
	{foreach from=$backtrace item=trace key=numero}
	<div class="trace" id="traza_{$numero}" onmouseover="Marcar('traza_{$numero}')" onmouseout="DesMarcar(this);">
		{$numero}: <a href="#top" onclick="AbrirArchivo('{$trace.file}', '{$trace.line}', 'traza_{$numero}');">{$trace.file_strip} (line {$trace.line})</a>: {$trace.class}{$trace.type}{$trace.function}({$trace.args})
	</div>
	{/foreach}
</div>
<script type="text/javascript">
	Marcar('traza_0');
</script>
</body>
</html>
{/if}