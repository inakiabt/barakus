<?php
function smarty_function_pr($params, &$smarty)
{
    echo "<div align=\"left\">";
    pr($params['param'], $params['exit']);
    echo "</div>";
}
?>
