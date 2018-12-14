<?php 
header('Content-type:text/html; charset=utf-8');
require_once 'config.php';

//read config
$config = new config();
if(!$config->init())
{
    echo "web config err!";
    exit;
}

//check cookie
if (!isset($_POST['login'])) 
{
    if($config->userSql->alreadyLogin())
        header('location:main/index.html');
    else
        header('location:login.html');
}
//check log user and pwd
else 
{
    $username = trim($_POST['user']);
    $password = trim($_POST['pwd']);

    if($config->userSql->login($username,$password))
        header('location:main/index.html');
    else 
        header('location:login.html');
}
?>
