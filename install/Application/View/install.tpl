{include file="header.tpl"}
<div style="text-align: center;">
<img src="img/logo.jpg" />
<br />
<br />
<h2>Bienvenido, se proceder&aacute; a crear la estructura de la aplicaci&oacute;n</h2>
<br />
<form action="?page=Generar" method="post" style="border: 1px dotted #AAA; width: 50%; padding: 30px; margin: 0 auto">
    <b>Path</b>: {$root_path}/
	<div style="display: inline;">
		<input type="text" name="path" value="{$dir_name}" style="width: 200px;" />
		<br /><small>Path donde quiere crear la estructura</small>
	</div>
	<br />
	<input type="submit" value="Instalar" />
</form>
</div>
{include file="footer.tpl"}