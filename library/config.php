<?php
header('Content-type:text/html; charset=utf-8');
require_once '../Helper/userHelper.php';
require_once '../Helper/libraryHelper.php';
require_once '../Helper/configHelper.php';

class config
{
    public  $userSql      = null;
    public  $bookSql      = null;
    private $configName   = "library.ini";
    
    public function init()
    {
        $configName= $this->configName;
        $sqlip     = getValue("sql", "ip", "localhost", $configName);
        $sqldbname = getValue("sql", "dbname", "library", $configName);
        $sqluser   = getValue("sql", "user", "", $configName);
        $sqlpwd    = getValue("sql", "pwd", "", $configName);

        if($sqluser == "" || $sqlpwd == "")
            return false;

        $userSql = new userHelper();
        $bookSql = new libraryHelper();
        $check1  = $userSql->connect($sqlip, $sqldbname, $sqluser, $sqlpwd);
        $check2  = $bookSql->connect($sqlip, $sqldbname, $sqluser, $sqlpwd);
        if(!$check1 || !$check2)
            return false;

        $this->userSql = $userSql;
        $this->bookSql = $bookSql;
        return true;
    }
}
?>