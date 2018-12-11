<?php
header('Content-type:text/html; charset=utf-8');
require_once 'mysqlHelper.php';
/****'user'TABLE****************************
 id   name   pwd   email   permission  
********************************************/

class userHelper
{
    private $mysqlTool = null;
    public  $user      = null;
    public  $pwd       = null;
    public  $islogin   = false;


    public function connect($ip, $dbname, $user, $pwd)
    {
        $con = new mysqlHelper();
        $ret = $con->connect($ip, $dbname, $user, $pwd);
        if(!$ret)
            return false;
        $this->mysqlTool = $con;
        return true;
    }

    private function getNewID()
    {
        $sql  = "select max(id) from user";
        $data = $this->conn->get($sql);
        if(count($data) <= 0)
            return 0;
        return $data[0]['max(id)'] + 1;
    }

    private function getUserInfo($user)
    {
        if($this->mysqlTool == null)
            return null;
        
        $sql  = "select * from `user` where name='$user'";
        $data = $this->mysqlTool->get($sql);
        if(count($data) <= 0)
            return null;
        
        return $data[0];
    }

    private function getUserCount()
    {
        if($this->mysqlTool == null)
            return 0;
        
        $sql  = "select * from `user`";
        $data = $this->mysqlTool->get($sql);
        return count($data);
    }

    public function getAllUser()
    {
        if($this->mysqlTool == null)
            return null;
        
        $sql  = "select * from `user`";
        $data = $this->mysqlTool->get($sql);
        return $data;
    }
    
    public function alreadyLogin()
    {
        if($this->islogin)
            return true;
        if(session_status() !== PHP_SESSION_ACTIVE)
            session_start();
        if (isset($_COOKIE['user']))
        {
            $_SESSION['user'] = $_COOKIE['user'];
            $_SESSION['pwd'] = $_COOKIE['pwd'];
            $check = $this->login($_SESSION['user'],$_SESSION['pwd']);
            if($check)
                return true;
        }
        return false;
    }

    public function login($user, $pwd, $save=TRUE)
    {
        $info = $this->getUserInfo($user);
        if($info == null)
            return false;
        
        if($info['pwd'] == $pwd)
        {
            $this->user    = $user;
            $this->pwd     = $pwd;
            $this->islogin = true;

            if(session_status() !== PHP_SESSION_ACTIVE)
                session_start();
            $_SESSION['user'] = $user;
            $_SESSION['pwd']  = $pwd;
            setcookie('user', $user, time()+7*24*60*60);
            setcookie('pwd', $pwd, time()+7*24*60*60);
            return true;
        }
        return false;
    }

    public function logout()
    {
        if(!$this->islogin)
            return;

        $this->islogin = false;
        $this->user    = null;
        $this->pwd     = null;

        if(session_status() !== PHP_SESSION_ACTIVE)
            session_start();
        $_SESSION = array();
        session_destroy();

        setcookie('user', '', time()-99);
	    setcookie('pwd', '', time()-99);
    }

    public function register($user, $pwd, $email, $permission)
    {
        if($user == "" || $pwd == "" || $email == "" || $permission == "")
            return false;

        $info = $this->getUserInfo($user);
        if($info !== null)
            return false;

        $id = $this->getNewID();
        $arr = array(
        'id'         => "$id",
        'name'       => "$user",
        'pwd'        => "$pwd",
        'email'      => "$email",
        'permission' => "$permission",
        );
        $res = $this->mysqlTool->insert('user', $arr);
        if($res == false)
            return false;
        
        $this->user    = $user;
        $this->pwd     = $pwd;
        $this->islogin = true;
        return true;
    }

}

$obj = new userHelper();
$obj->connect("144.34.241.208","aiglibrary","yaron","huang");
$obj->alreadyLogin();
$obj->login("yaron","123456");

?>