<?php
class Authenticable extends Doctrine_Template
{
    private $_username_field = 'username';
    private $_password_field = 'password';
    private $_findByMethod;
    
    public function __construct(array $options)
    {
        if (isset($options['username']))
        {
            $this->_username_field = $options['username'];
        }
        if (isset($options['password']))
        {
            $this->_password_field = $options['password'];
        }
        $this->_findByMethod = 'findOneBy' . ucfirst($this->_username_field);
    }
    public function login($username, $password)
    {
        $findByMethod = $this->_findByMethod;
        return ($password === $this->getTable()->{$findByMethod}($username)->password);
    }
}
?>
