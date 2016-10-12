<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * Type:     block
 * Name:     rss
 * Filename: block.rss.php   
 * Version:  1.0
 * Date:     September 17, 2004
 * Author:   webtigo.ch (info@webtigo.ch)
 * Purpose:  iterates over a rss feed
 * Requires: * pear XML_RSS lib: http://pear.php.net
 * Install:  * put block.rss.php in plugin dir
 *           * call from within a template
 *
 * Input:    file = local file or URL of rss
 *           length= number of items to parse (optional) 
 *           if not defined all the file will be parsed 
 * 
 *
 * Example:  last three php news:
 *
 *           {rss file="http://www.php.net/news.rss" length="3"}
 *             <a href="{$rss_item.link}" target=_blank> {$rss_item.title} </a><br>
 *           {/rss}
 * -------------------------------------------------------------
 */

function smarty_block_rss ($params, $content, &$smarty,&$repeat) 
{

    $md5 = $params["file"];
    if (!is_array($GLOBALS['__SMARTY_RSS'][$md5])) 
    {
        require_once "XML/RSS.php";
        $rss =& new XML_RSS($params["file"]);
        $rss->parse();
        $res=$rss->getItems();
        if($params['length'] > 0)
        {
            $res=array_slice($res,0,$params['length']+1);
        }
        $GLOBALS['__SMARTY_RSS'][$md5] = $res;
    }
                                     
    $smarty->assign("rss_item", array_shift($GLOBALS['__SMARTY_RSS'][$md5]));
    
    if (count($GLOBALS['__SMARTY_RSS'][$md5]) == 0)
    {
        $repeat = false;
    } else {
        $repeat = true;
    }
    
    if (!is_null($content))
    {
        return $content;
    }
}
?>