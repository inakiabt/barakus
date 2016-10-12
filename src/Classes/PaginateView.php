<?php

class PaginateView
{
    var $id       = 'p';
    var $paginate = array();
    var $url_var  = 'next';
    var $view;
    
    function PaginateView(&$view, $id = 'p')
    {
        $this->id       = $id;
        $this->view     = &$view;
        $this->paginate[$id] = array(
            'item_limit' => false,
            'item_total' => null,
            'current_item' => 1,
            'urlvar' => 'next',
            'url' => Server::get('REQUEST_URI'),
            'prev_text' => 'prev',
            'next_text' => 'next',
            'first_text' => 'first',
            'last_text' => 'last'
            );        
    }
    
    function paginate()
    {
        $this->assign('paginate_' . ($this->id == 'p' ? '' : $this->id));
    }
    
    function getUrl() {
        return $this->paginate[$this->id]['url'];
    }    
    
    function addUrlVar($array)
    {
        $this->paginate[$this->id]['url'] .= '&'.http_build_query($array);
    }

    /**
     * set maximum number of items per page
     *
     * @param string $id the pagination id
     */
    function setLimit($limit) 
    {
        if(!preg_match('!^\d+$!', $limit)) {
            trigger_error('Paginate setLimit: limit must be an integer.');
            return false;
        }
        settype($limit, 'integer');
        if($limit < 1) {
            trigger_error('Paginate setLimit: limit must be greater than zero.');
            return false;
        }        
        return $this->paginate[$this->id]['item_limit'] = $limit;
    }    

    /**
     * get maximum number of items per page
     *
     * @param string $id the pagination id
     */
    function getLimit() 
    {
        return $this->paginate[$this->id]['item_limit'];
    }    
            
    /**
     * set the total number of items
     *
     * @param int $total the total number of items
     * @param string $id the pagination id
     */
    function setTotal($total) 
    {
        $this->paginate[$this->id]['item_total'] = $total;
    }

    /**
     * get the total number of items
     *
     * @param string $id the pagination id
     */
    function getTotal() 
    {
        return $this->paginate[$this->id]['item_total'];
    }    
    
    /**
     * set the url variable ie. ?next=10
     *                           ^^^^
     * @param string $url url pagination varname
     * @param string $id the pagination id
     */
    function setUrlVar($urlvar) 
    {
        $this->paginate[$this->id]['urlvar'] = $urlvar;
    }

    /**
     * get the url variable
     *
     * @param string $id the pagination id
     */
    function getUrlVar() 
    {
        return $this->paginate[$this->id]['urlvar'];
    }    
        
    /**
     * set the current item (usually done automatically by next/prev links)
     *
     * @param int $item index of the current item
     * @param string $id the pagination id
     */
    function setCurrentItem($item) 
    {
        $this->paginate[$this->id]['current_item'] = $item;
    }

    /**
     * get the current item
     *
     * @param string $id the pagination id
     */
    function getCurrentItem() 
    {
        if(Request::get('pagids') == $this->id)
        {
            $this->paginate[$this->id]['current_item'] = Request::intval($this->paginate[$this->id]['urlvar']);
        }       
        return $this->paginate[$this->id]['current_item'];
    }    

    /**
     * get the current item index
     *
     * @param string $id the pagination id
     */
    function getCurrentIndex() 
    {
        return $this->getCurrentItem() - 1;
    }    
    
    function getCurrentPage()
    {
        if (!$this->paginate[$this->id]['item_limit'])
        {
            trigger_error('Paginate getCurrentPage: limit must be setted until call this method.');
            return false;
        }
        return intval($this->getCurrentItem() / $this->getLimit()) + 1;
    }
    
    /**
     * get the last displayed item
     *
     * @param string $id the pagination id
     */
    function getLastItem() 
    {
        $_total = $this->getTotal($this->id);
        $_limit = $this->getLimit($this->id);
        $_last  = $this->getCurrentItem($this->id) + $_limit - 1;
        return ($_last <= $_total) ? $_last : $_total;
    }    
    
    /**
     * assign $paginate var values
     *
     * @param obj &$smarty the smarty object reference
     * @param string $var the name of the assigned var
     * @param string $id the pagination id
     */
    function assign($var = 'paginate') 
    {
            $_paginate['total']        = $this->getTotal($this->id);
            $_paginate['first']        = $this->getCurrentItem($this->id);
            $_paginate['last']         = $this->getLastItem($this->id);
            $_paginate['page_current'] = ceil($this->getLastItem($this->id) / $this->getLimit($this->id));
            $_paginate['page_total']   = ceil($this->getTotal($this->id)/$this->getLimit($this->id));
            $_paginate['size']         = $_paginate['last'] - $_paginate['first'];
            $_paginate['url']          = $this->getUrl($this->id);
            $_paginate['urlvar']       = $this->getUrlVar($this->id);
            $_paginate['current_item'] = $this->getCurrentItem($this->id);
            $_paginate['limit']        = $this->getLimit($this->id);
            
            $_item = 1;
            $_page = 1;
            while($_item <= $_paginate['total'])           {
                $_paginate['page'][$_page]['number']     = $_page;   
                $_paginate['page'][$_page]['item_start'] = $_item;
                $_paginate['page'][$_page]['item_end']   = ($_item + $_paginate['limit'] - 1 <= $_paginate['total']) ? $_item + $_paginate['limit'] - 1 : $_paginate['total'];
                $_paginate['page'][$_page]['is_current'] = ($_item == $_paginate['current_item']);
                $_item += $_paginate['limit'];
                $_page++;
            }
            $this->view->assign($var, $_paginate);
    }    

    /**
     * set default number of page groupings in {paginate_middle}
     *
     * @param string $id the pagination id
     */
    function setPageLimit($limit) {
        if(!preg_match('!^\d+$!', $limit)) {
            trigger_error('Paginate setPageLimit: limit must be an integer.');
            return false;
        }
        settype($limit, 'integer');
        if($limit < 1) {
            trigger_error('Paginate setPageLimit: limit must be greater than zero.');
            return false;
        }
        $this->paginate[$this->id]['page_limit'] = $limit;
    }    

    /**
     * get default number of page groupings in {paginate_middle}
     *
     * @param string $id the pagination id
     */
    function getPageLimit() 
    {
        return $this->paginate[$this->id]['page_limit'];
    }
            
    /**
     * get the previous page of items
     *
     * @param string $id the pagination id
     */
    function _getPrevPageItem() {
        
        $_prev_item = $this->paginate[$this->id]['current_item'] - $this->paginate[$this->id]['item_limit'];
        
        return ($_prev_item > 0) ? $_prev_item : false; 
    }    

    /**
     * get the previous page of items
     *
     * @param string $id the pagination id
     */
    function _getNextPageItem() {
                
        $_next_item = $this->paginate[$this->id]['current_item'] + $this->paginate[$this->id]['item_limit'];
        
        return ($_next_item <= $this->paginate[$this->id]['item_total']) ? $_next_item : false; 
    }    
   
    
}
?>
