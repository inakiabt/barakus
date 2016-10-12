<code>
    <span style="color: #0000BB;">&lt;?php&nbsp;</span><span style="color: #0000BB;">&nbsp;</span><span style="color: #FF8000;">//&nbsp;vim:&nbsp;expandtab&nbsp;sw=4&nbsp;ts=4&nbsp;fdm=marker</span><br />
    <span style="color: #FF8000;">/**</span><br />
    <span style="color: #FF8000;">&nbsp;*&nbsp;&nbsp;Class&nbsp;to&nbsp;validate&nbsp;user&nbsp;input</span><br />
    <span style="color: #FF8000;">&nbsp;*&nbsp;&nbsp;A&nbsp;simple&nbsp;wrapper&nbsp;class&nbsp;for&nbsp;various&nbsp;validatiung&nbsp;techniques.&nbsp;&nbsp;uses&nbsp;regrex,&nbsp;ctype,&nbsp;date&nbsp;and&nbsp;string&nbsp;</span><br />
    <span style="color: #FF8000;">&nbsp;*&nbsp;&nbsp;functions&nbsp;to&nbsp;get&nbsp;to&nbsp;a&nbsp;simple&nbsp;TRUE/FALSE&nbsp;answer.&nbsp;&nbsp;Class&nbsp;can&nbsp;be&nbsp;called&nbsp;without&nbsp;instantiation.</span><br />
    <span style="color: #FF8000;">&nbsp;*&nbsp;&nbsp;Access&nbsp;with&nbsp;the&nbsp;CLASS::METHOD&nbsp;syntax.</span><br />
    <span style="color: #FF8000;">&nbsp;*/</span><br />
    &nbsp;<br />
    <span style="color: #007700;">class</span><span style="color: #0000BB;">&nbsp;</span><span style="color: #0000BB;">Validate</span><br />
    <span style="color: #007700;">{</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #FF8000;">/*&nbsp;{{{isNotEmpty&nbsp;*/</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #FF8000;">/**</span><br />
    <span style="color: #FF8000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;Method&nbsp;to&nbsp;see&nbsp;if&nbsp;a&nbsp;value&nbsp;has&nbsp;been&nbsp;entered.&nbsp;&nbsp;The&nbsp;following&nbsp;are&nbsp;considered&nbsp;to&nbsp;be&nbsp;empty:</span><br />
    <span style="color: #FF8000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;</span><br />
    <span style="color: #FF8000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&quot;&quot;&nbsp;(an&nbsp;empty&nbsp;string)</span><br />
    <span style="color: #FF8000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;0&nbsp;(0&nbsp;as&nbsp;an&nbsp;integer)</span><br />
    <span style="color: #FF8000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&quot;0&quot;&nbsp;(0&nbsp;as&nbsp;a&nbsp;string)&nbsp;&nbsp;&nbsp;&lt;---&nbsp;Please&nbsp;note&nbsp;for&nbsp;when&nbsp;dealing&nbsp;with&nbsp;forms</span><br />
    <span style="color: #FF8000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;NULL</span><br />
    <span style="color: #FF8000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;FALSE</span><br />
    <span style="color: #FF8000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;array()&nbsp;(an&nbsp;empty&nbsp;array)</span><br />
    <span style="color: #FF8000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;var&nbsp;$var;&nbsp;(a&nbsp;variable&nbsp;declared,&nbsp;but&nbsp;without&nbsp;a&nbsp;value&nbsp;in&nbsp;a&nbsp;class)</span><br />
    <span style="color: #FF8000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*/</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #007700;">function</span><span style="color: #0000BB;">&nbsp;</span><span style="color: #0000BB;">isNotEmpty</span><span style="color: #007700;">(</span><span style="color: #0000BB;">$input</span><span style="color: #007700;">)</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #007700;">{</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #007700;">return</span><span style="color: #0000BB;">&nbsp;</span><span style="color: #007700;">!</span><span style="color: #007700;">empty</span><span style="color: #007700;">(</span><span style="color: #0000BB;">$input</span><span style="color: #007700;">)</span><span style="color: #007700;">;</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #007700;">}</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #FF8000;">/*&nbsp;}}}isNotEmpty&nbsp;*/</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #FF8000;">/*&nbsp;{{{isAlpha&nbsp;*/</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #FF8000;">/**</span><br />
    <span style="color: #FF8000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;Method&nbsp;to&nbsp;check&nbsp;for&nbsp;all&nbsp;ASCII&nbsp;alphabetic&nbsp;characters&nbsp;(a-z&nbsp;A-Z).&nbsp;&nbsp;Blank</span><br />
    <span style="color: #FF8000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;spaces&nbsp;and&nbsp;underscores&nbsp;are&nbsp;not&nbsp;allowed.</span><br />
    <span style="color: #FF8000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*/</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #007700;">function</span><span style="color: #0000BB;">&nbsp;</span><span style="color: #0000BB;">isAlpha</span><span style="color: #007700;">(</span><span style="color: #0000BB;">$input</span><span style="color: #007700;">)</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #007700;">{</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #007700;">return</span><span style="color: #0000BB;">&nbsp;</span><span style="color: #0000BB;"><a href="http://www.php.net/function.ctype_alpha">ctype_alpha</a></span><span style="color: #007700;">(</span><span style="color: #0000BB;">$input</span><span style="color: #007700;">)</span><span style="color: #007700;">;</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #007700;">}</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #FF8000;">/*&nbsp;}}}isAlpha&nbsp;*/</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #FF8000;">/*&nbsp;{{{isAlphaNum&nbsp;*/</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #FF8000;">/**</span><br />
    <span style="color: #FF8000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;Method&nbsp;to&nbsp;check&nbsp;for&nbsp;alpha-numeric&nbsp;characters&nbsp;only&nbsp;(a-z&nbsp;A-Z&nbsp;0-9).&nbsp;&nbsp;Blank</span><br />
    <span style="color: #FF8000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;spaces&nbsp;and&nbsp;underscores&nbsp;are&nbsp;not&nbsp;allowed.</span><br />
    <span style="color: #FF8000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*/</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #007700;">function</span><span style="color: #0000BB;">&nbsp;</span><span style="color: #0000BB;">isAlphaNum</span><span style="color: #007700;">(</span><span style="color: #0000BB;">$input</span><span style="color: #007700;">)</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #007700;">{</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #007700;">return</span><span style="color: #0000BB;">&nbsp;</span><span style="color: #0000BB;"><a href="http://www.php.net/function.ctype_alnum">ctype_alnum</a></span><span style="color: #007700;">(</span><span style="color: #0000BB;">$input</span><span style="color: #007700;">)</span><span style="color: #007700;">;</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #007700;">}</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #FF8000;">/*&nbsp;}}}isAlphaNum&nbsp;*/</span><br />
    &nbsp;<br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #FF8000;">/*&nbsp;{{{isAlNumUnder&nbsp;*/</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #FF8000;">/**</span><br />
    <span style="color: #FF8000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;Method&nbsp;to&nbsp;check&nbsp;for&nbsp;alpha-numeric&nbsp;and&nbsp;underscore&nbsp;characters.&nbsp;&nbsp;Blank&nbsp;spaces&nbsp;</span><br />
    <span style="color: #FF8000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;aren't&nbsp;allowed.</span><br />
    <span style="color: #FF8000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*/</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #007700;">function</span><span style="color: #0000BB;">&nbsp;</span><span style="color: #0000BB;">isAlNumUnder</span><span style="color: #007700;">(</span><span style="color: #0000BB;">$input</span><span style="color: #007700;">)</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #007700;">{</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #007700;">return</span><span style="color: #0000BB;">&nbsp;</span><span style="color: #0000BB;"><a href="http://www.php.net/function.preg_match">preg_match</a></span><span style="color: #007700;">(</span><span style="color: #DD0000;">'/^[a-zA-Z0-9_]+$/'</span><span style="color: #007700;">,</span><span style="color: #0000BB;">&nbsp;</span><span style="color: #0000BB;">$input</span><span style="color: #007700;">)</span><span style="color: #007700;">?</span><span style="color: #0000BB;">TRUE</span><span style="color: #007700;">:</span><span style="color: #0000BB;">FALSE</span><span style="color: #007700;">;</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #007700;">}</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #FF8000;">/*&nbsp;}}}isAlNumUnder&nbsp;*/</span><br />
    &nbsp;<br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #FF8000;">/*&nbsp;{{{isInteger&nbsp;*/</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #FF8000;">/**</span><br />
    <span style="color: #FF8000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;Method&nbsp;to&nbsp;check&nbsp;for&nbsp;Integer&nbsp;characters&nbsp;(whole&nbsp;numbers&nbsp;only).&nbsp;&nbsp;</span><br />
    <span style="color: #FF8000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*/</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #007700;">function</span><span style="color: #0000BB;">&nbsp;</span><span style="color: #0000BB;">isInteger</span><span style="color: #007700;">(</span><span style="color: #0000BB;">$input</span><span style="color: #007700;">)</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #007700;">{</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #007700;">return</span><span style="color: #0000BB;">&nbsp;</span><span style="color: #0000BB;"><a href="http://www.php.net/function.preg_match">preg_match</a></span><span style="color: #007700;">(</span><span style="color: #DD0000;">'/^[0-9]+$/'</span><span style="color: #007700;">,</span><span style="color: #0000BB;">$input</span><span style="color: #007700;">)</span><span style="color: #007700;">?</span><span style="color: #0000BB;">TRUE</span><span style="color: #007700;">:</span><span style="color: #0000BB;">FALSE</span><span style="color: #007700;">;</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #007700;">}</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #FF8000;">/*&nbsp;}}}isInteger&nbsp;*/</span><br />
    &nbsp;<br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #FF8000;">/*&nbsp;{{{isFloat&nbsp;*/</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #FF8000;">/**</span><br />
    <span style="color: #FF8000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;Method&nbsp;to&nbsp;check&nbsp;for&nbsp;a&nbsp;float&nbsp;value&nbsp;(decimal).&nbsp;&nbsp;Scientific&nbsp;notation&nbsp;is&nbsp;not&nbsp;</span><br />
    <span style="color: #FF8000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;supported.&nbsp;&nbsp;A&nbsp;decimal&nbsp;point&nbsp;is&nbsp;required.&nbsp;&nbsp;See&nbsp;isInteger()&nbsp;if&nbsp;the&nbsp;decimal</span><br />
    <span style="color: #FF8000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;is&nbsp;left&nbsp;out.</span><br />
    <span style="color: #FF8000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*/</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #007700;">function</span><span style="color: #0000BB;">&nbsp;</span><span style="color: #0000BB;">isFloat</span><span style="color: #007700;">(</span><span style="color: #0000BB;">$input</span><span style="color: #007700;">)</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #007700;">{</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #007700;">return</span><span style="color: #0000BB;">&nbsp;</span><span style="color: #0000BB;"><a href="http://www.php.net/function.preg_match">preg_match</a></span><span style="color: #007700;">(</span><span style="color: #DD0000;">'/^[0-9]{1,10}[.][0-9]{1,10}$/'</span><span style="color: #007700;">,</span><span style="color: #0000BB;">&nbsp;</span><span style="color: #0000BB;">$input</span><span style="color: #007700;">)</span><span style="color: #007700;">?</span><span style="color: #0000BB;">TRUE</span><span style="color: #007700;">:</span><span style="color: #0000BB;">FALSE</span><span style="color: #007700;">;</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #007700;">}</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #FF8000;">/*&nbsp;}}}isFloat&nbsp;*/</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #FF8000;">/*&nbsp;{{{validTimestamp&nbsp;*/</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #FF8000;">/**</span><br />
    <span style="color: #FF8000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;Method&nbsp;to&nbsp;make&nbsp;sure&nbsp;a&nbsp;timestamp&nbsp;is&nbsp;a&nbsp;valid&nbsp;date.&nbsp;</span><br />
    <span style="color: #FF8000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*/</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #007700;">function</span><span style="color: #0000BB;">&nbsp;</span><span style="color: #0000BB;">validTimestamp</span><span style="color: #007700;">(</span><span style="color: #0000BB;">$input</span><span style="color: #007700;">)</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #007700;">{</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #007700;">return</span><span style="color: #0000BB;">&nbsp;</span><span style="color: #0000BB;"><a href="http://www.php.net/function.checkdate">checkdate</a></span><span style="color: #007700;">(</span><span style="color: #0000BB;"><a href="http://www.php.net/function.date">date</a></span><span style="color: #007700;">(</span><span style="color: #DD0000;">'n'</span><span style="color: #007700;">,</span><span style="color: #0000BB;">$input</span><span style="color: #007700;">)</span><span style="color: #007700;">,</span><span style="color: #0000BB;">&nbsp;</span><span style="color: #0000BB;"><a href="http://www.php.net/function.date">date</a></span><span style="color: #007700;">(</span><span style="color: #DD0000;">'j'</span><span style="color: #007700;">,</span><span style="color: #0000BB;">&nbsp;</span><span style="color: #0000BB;">$input</span><span style="color: #007700;">)</span><span style="color: #007700;">,</span><span style="color: #0000BB;">&nbsp;</span><span style="color: #0000BB;"><a href="http://www.php.net/function.date">date</a></span><span style="color: #007700;">(</span><span style="color: #DD0000;">'Y'</span><span style="color: #007700;">,</span><span style="color: #0000BB;">&nbsp;</span><span style="color: #0000BB;">$input</span><span style="color: #007700;">)</span><span style="color: #007700;">)</span><span style="color: #007700;">;</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #007700;">}</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #FF8000;">/*&nbsp;}}}validTimestamp&nbsp;*/</span><br />
    &nbsp;<br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #FF8000;">/*&nbsp;{{{validEmail&nbsp;*/</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #FF8000;">/**</span><br />
    <span style="color: #FF8000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;Method&nbsp;to&nbsp;validate&nbsp;an&nbsp;email&nbsp;address.&nbsp;&nbsp;regrex&nbsp;pattern&nbsp;taken&nbsp;from&nbsp;regexlib.com&nbsp;</span><br />
    <span style="color: #FF8000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;(http://www.regexlib.com/Default.aspx)&nbsp;submitted&nbsp;there&nbsp;by&nbsp;Myle&nbsp;Ott.&nbsp;&nbsp;allows&nbsp;</span><br />
    <span style="color: #FF8000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;both&nbsp;IP&nbsp;addresses&nbsp;and&nbsp;regular&nbsp;domains.</span><br />
    <span style="color: #FF8000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*/</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #007700;">function</span><span style="color: #0000BB;">&nbsp;</span><span style="color: #0000BB;">validEmail</span><span style="color: #007700;">(</span><span style="color: #0000BB;">$input</span><span style="color: #007700;">)</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #007700;">{</span><span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #0000BB;">$pattern</span><span style="color: #0000BB;">&nbsp;</span><span style="color: #007700;">=</span><span style="color: #0000BB;">&nbsp;</span><span style="color: #DD0000;">'^([a-zA-Z0-9_\-])+(\.([a-zA-Z0-9_\-])+)*@((\[(((([0-1])?([0-9])?[0-9])|(2[0-4][0-9])|(2[0-5][0-5])))\.(((([0-1])?([0-9])?[0-9])|(2[0-4][0-9])|(2[0-5][0-5])))\.(((([0-1])?([0-9])?[0-9])|(2[0-4][0-9])|(2[0-5][0-5])))\.(((([0-1])?([0-9])?[0-9])|(2[0-4][0-9])|(2[0-5][0-5]))\]))|((([a-zA-Z0-9])+(([\-])+([a-zA-Z0-9])+)*\.)+([a-zA-Z])+(([\-])+([a-zA-Z0-9])+)*))$'</span><span style="color: #007700;">;</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #007700;">return</span><span style="color: #0000BB;">&nbsp;</span><span style="color: #0000BB;"><a href="http://www.php.net/function.preg_match">preg_match</a></span><span style="color: #007700;">(</span><span style="color: #0000BB;">&nbsp;</span><span style="color: #0000BB;">$pattern</span><span style="color: #007700;">,</span><span style="color: #0000BB;">&nbsp;</span><span style="color: #0000BB;">$input</span><span style="color: #007700;">)</span><span style="color: #007700;">?</span><span style="color: #0000BB;">TRUE</span><span style="color: #007700;">:</span><span style="color: #0000BB;">FALSE</span><span style="color: #007700;">;</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #007700;">}</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #FF8000;">/*&nbsp;}}}validEmail&nbsp;*/</span><br />
    &nbsp;<br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #FF8000;">/*&nbsp;{{{validUrl&nbsp;*/</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #FF8000;">/**</span><br />
    <span style="color: #FF8000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;Method&nbsp;to&nbsp;validate&nbsp;a&nbsp;full&nbsp;URL.&nbsp;&nbsp;Regex&nbsp;pattern&nbsp;copied&nbsp;from&nbsp;regrexlib.com</span><br />
    <span style="color: #FF8000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;(http://www.regexlib.com/Default.aspx)&nbsp;no&nbsp;attribution&nbsp;was&nbsp;given&nbsp;for&nbsp;the&nbsp;</span><br />
    <span style="color: #FF8000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;author.&nbsp;Iit&nbsp;will&nbsp;NOT&nbsp;match&nbsp;a&nbsp;valid&nbsp;URL&nbsp;ending&nbsp;with&nbsp;a&nbsp;dot&nbsp;or&nbsp;bracket</span><br />
    <span style="color: #FF8000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*/</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #007700;">function</span><span style="color: #0000BB;">&nbsp;</span><span style="color: #0000BB;">validUrl</span><span style="color: #007700;">(</span><span style="color: #0000BB;">$input</span><span style="color: #007700;">)</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #007700;">{</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #0000BB;">$pattern</span><span style="color: #0000BB;">&nbsp;</span><span style="color: #007700;">=</span><span style="color: #0000BB;">&nbsp;</span><span style="color: #DD0000;">'^(http|https|ftp)\://[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(:[a-zA-Z0-9]*)?/?([a-zA-Z0-9\-\._\?\,\'/\\\+&amp;%\$#\=~])*[^\.\,\)\(\s]$'</span><span style="color: #007700;">;</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #007700;">return</span><span style="color: #0000BB;">&nbsp;</span><span style="color: #0000BB;"><a href="http://www.php.net/function.preg_match">preg_match</a></span><span style="color: #007700;">(</span><span style="color: #0000BB;">$pattern</span><span style="color: #007700;">,</span><span style="color: #0000BB;">$input</span><span style="color: #007700;">)</span><span style="color: #007700;">?</span><span style="color: #0000BB;">TRUE</span><span style="color: #007700;">:</span><span style="color: #0000BB;">FALSE</span><span style="color: #007700;">;</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #007700;">}</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #FF8000;">/*&nbsp;}}}validUrl&nbsp;*/</span><br />
    &nbsp;<br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #FF8000;">/*&nbsp;{{{validUSPhone&nbsp;*/</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #FF8000;">/**</span><br />
    <span style="color: #FF8000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;Method&nbsp;to&nbsp;validate&nbsp;a&nbsp;US&nbsp;phone&nbsp;number.&nbsp;&nbsp;regex&nbsp;pattern&nbsp;taken&nbsp;off&nbsp;of&nbsp;</span><br />
    <span style="color: #FF8000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;the&nbsp;PHP&nbsp;manual&nbsp;user&nbsp;comments&nbsp;for&nbsp;preg_match.&nbsp;&nbsp;Author&nbsp;is&nbsp;unknown.</span><br />
    <span style="color: #FF8000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;Valid&nbsp;formats:</span><br />
    <span style="color: #FF8000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;</span><br />
    <span style="color: #FF8000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;(Xxx)&nbsp;Xxx-Xxxx</span><br />
    <span style="color: #FF8000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;(Xxx)&nbsp;Xxx&nbsp;Xxxx</span><br />
    <span style="color: #FF8000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;Xxx&nbsp;Xxx&nbsp;Xxxx</span><br />
    <span style="color: #FF8000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;Xxx-Xxx-Xxxx</span><br />
    <span style="color: #FF8000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;XxxXxxXxxx</span><br />
    <span style="color: #FF8000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;Xxx.Xxx.Xxxx</span><br />
    <span style="color: #FF8000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;</span><br />
    <span style="color: #FF8000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*/</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #007700;">function</span><span style="color: #0000BB;">&nbsp;</span><span style="color: #0000BB;">validUSPhone</span><span style="color: #007700;">(</span><span style="color: #0000BB;">$input</span><span style="color: #007700;">)</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #007700;">{</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #007700;">return</span><span style="color: #0000BB;">&nbsp;</span><span style="color: #0000BB;"><a href="http://www.php.net/function.preg_match">preg_match</a></span><span style="color: #007700;">(</span><span style="color: #007700;">&quot;</span><span style="color: #0000BB;">/^(</span><span style="color: #0000BB;">\(</span><span style="color: #0000BB;">|)</span><span style="color: #007700;">{</span><span style="color: #0000BB;">1</span><span style="color: #007700;">}</span><span style="color: #007700;">[</span><span style="color: #0000BB;">2</span><span style="color: #0000BB;">-</span><span style="color: #0000BB;">9</span><span style="color: #007700;">]</span><span style="color: #007700;">[</span><span style="color: #0000BB;">0</span><span style="color: #0000BB;">-</span><span style="color: #0000BB;">9</span><span style="color: #007700;">]</span><span style="color: #007700;">{</span><span style="color: #0000BB;">2</span><span style="color: #007700;">}</span><span style="color: #0000BB;">(</span><span style="color: #0000BB;">\)</span><span style="color: #0000BB;">|)</span><span style="color: #007700;">{</span><span style="color: #0000BB;">1</span><span style="color: #007700;">}</span><span style="color: #0000BB;">(</span><span style="color: #007700;">[</span><span style="color: #0000BB;">\.</span><span style="color: #0000BB;">-&nbsp;</span><span style="color: #007700;">]</span><span style="color: #0000BB;">|)</span><span style="color: #007700;">[</span><span style="color: #0000BB;">2</span><span style="color: #0000BB;">-</span><span style="color: #0000BB;">9</span><span style="color: #007700;">]</span><span style="color: #007700;">[</span><span style="color: #0000BB;">0</span><span style="color: #0000BB;">-</span><span style="color: #0000BB;">9</span><span style="color: #007700;">]</span><span style="color: #007700;">{</span><span style="color: #0000BB;">2</span><span style="color: #007700;">}</span><span style="color: #0000BB;">(</span><span style="color: #007700;">[</span><span style="color: #0000BB;">\.</span><span style="color: #0000BB;">-&nbsp;</span><span style="color: #007700;">]</span><span style="color: #0000BB;">|)</span><span style="color: #007700;">[</span><span style="color: #0000BB;">0</span><span style="color: #0000BB;">-</span><span style="color: #0000BB;">9</span><span style="color: #007700;">]</span><span style="color: #007700;">{</span><span style="color: #0000BB;">4</span><span style="color: #007700;">}</span><span style="color: #0000BB;">$</span><span style="color: #0000BB;">/</span><span style="color: #007700;">&quot;</span><span style="color: #007700;">,</span><span style="color: #0000BB;">&nbsp;</span><span style="color: #0000BB;">$input</span><span style="color: #007700;">)</span><span style="color: #007700;">?</span><span style="color: #0000BB;">TRUE</span><span style="color: #007700;">:</span><span style="color: #0000BB;">FALSE</span><span style="color: #007700;">;</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #007700;">}</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #FF8000;">/*&nbsp;}}}validUSPhone&nbsp;*/</span><br />
    &nbsp;<br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #FF8000;">/*&nbsp;{{{validDate&nbsp;*/</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #FF8000;">/**</span><br />
    <span style="color: #FF8000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;Method&nbsp;to&nbsp;validate&nbsp;the&nbsp;combination&nbsp;of&nbsp;month,&nbsp;day&nbsp;and&nbsp;year.&nbsp;&nbsp;Leap</span><br />
    <span style="color: #FF8000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;year&nbsp;is&nbsp;taken&nbsp;into&nbsp;account.</span><br />
    <span style="color: #FF8000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*/</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #007700;">function</span><span style="color: #0000BB;">&nbsp;</span><span style="color: #0000BB;">validDate</span><span style="color: #007700;">(</span><span style="color: #0000BB;">$month</span><span style="color: #007700;">,</span><span style="color: #0000BB;">&nbsp;</span><span style="color: #0000BB;">$day</span><span style="color: #007700;">,</span><span style="color: #0000BB;">&nbsp;</span><span style="color: #0000BB;">$year</span><span style="color: #007700;">)</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #007700;">{</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #007700;">if</span><span style="color: #007700;">(</span><span style="color: #0000BB;"><a href="http://www.php.net/function.trim">trim</a></span><span style="color: #007700;">(</span><span style="color: #0000BB;">$month</span><span style="color: #007700;">)</span><span style="color: #007700;">&gt;</span><span style="color: #0000BB;">12</span><span style="color: #0000BB;">&nbsp;</span><span style="color: #007700;">||</span><span style="color: #0000BB;">&nbsp;</span><span style="color: #0000BB;"><a href="http://www.php.net/function.trim">trim</a></span><span style="color: #007700;">(</span><span style="color: #0000BB;">$day</span><span style="color: #007700;">)</span><span style="color: #007700;">&gt;</span><span style="color: #0000BB;">31</span><span style="color: #0000BB;">&nbsp;</span><span style="color: #007700;">||</span><span style="color: #0000BB;">&nbsp;</span><span style="color: #0000BB;"><a href="http://www.php.net/function.strlen">strlen</a></span><span style="color: #007700;">(</span><span style="color: #0000BB;"><a href="http://www.php.net/function.trim">trim</a></span><span style="color: #007700;">(</span><span style="color: #0000BB;">$year</span><span style="color: #007700;">)</span><span style="color: #007700;">)</span><span style="color: #0000BB;">&nbsp;</span><span style="color: #0000BB;">!=</span><span style="color: #0000BB;">&nbsp;</span><span style="color: #0000BB;">4</span><span style="color: #007700;">)</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #007700;">{</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #007700;">return</span><span style="color: #0000BB;">&nbsp;</span><span style="color: #0000BB;">FALSE</span><span style="color: #007700;">;</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #007700;">}</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #007700;">return</span><span style="color: #0000BB;">&nbsp;</span><span style="color: #0000BB;"><a href="http://www.php.net/function.checkdate">checkdate</a></span><span style="color: #007700;">(</span><span style="color: #0000BB;">$month</span><span style="color: #007700;">,</span><span style="color: #0000BB;">&nbsp;</span><span style="color: #0000BB;">$day</span><span style="color: #007700;">,</span><span style="color: #0000BB;">&nbsp;</span><span style="color: #0000BB;">$year</span><span style="color: #007700;">)</span><span style="color: #007700;">;</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #007700;">}</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #FF8000;">/*&nbsp;}}}validDate&nbsp;*/</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #FF8000;">/*&nbsp;{{{validLength&nbsp;*/</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #FF8000;">/**</span><br />
    <span style="color: #FF8000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;Method&nbsp;to&nbsp;validate&nbsp;the&nbsp;length&nbsp;of&nbsp;the&nbsp;string&nbsp;is&nbsp;less&nbsp;then&nbsp;the&nbsp;</span><br />
    <span style="color: #FF8000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;maximum&nbsp;length.</span><br />
    <span style="color: #FF8000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*/</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #007700;">function</span><span style="color: #0000BB;">&nbsp;</span><span style="color: #0000BB;">validLength</span><span style="color: #007700;">(</span><span style="color: #0000BB;">$input</span><span style="color: #007700;">,</span><span style="color: #0000BB;">&nbsp;</span><span style="color: #0000BB;">$max</span><span style="color: #007700;">)</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #007700;">{</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #007700;">return</span><span style="color: #0000BB;">&nbsp;</span><span style="color: #007700;">(</span><span style="color: #0000BB;"><a href="http://www.php.net/function.strlen">strlen</a></span><span style="color: #007700;">(</span><span style="color: #0000BB;">$input</span><span style="color: #007700;">)</span><span style="color: #0000BB;">&nbsp;</span><span style="color: #007700;">&lt;=</span><span style="color: #0000BB;">&nbsp;</span><span style="color: #0000BB;">$max</span><span style="color: #007700;">)</span><span style="color: #007700;">;</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #007700;">}</span><br />
    <span style="color: #0000BB;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #FF8000;">/*&nbsp;}}}validLength&nbsp;*/</span><br />
    <span style="color: #007700;">}</span><br />
    <span style="color: #0000BB;">?&gt;</span><br />
    &nbsp;<br />
</code>
