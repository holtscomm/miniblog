<?php

class User
{
    private $username;
    private $password;
    private $userid;

    function __construct($username, $password, $userid)
    {
        $this->username = $username;
        $this->password = $password;
        $this->userid = $userid;
    }

    function __destruct()
    {
        unset($this);
    }

    function get_user_id()
    {
        return $this->userid;
    }

    function is_user()
    {
        return $this->account_type == USER;
    }
}
