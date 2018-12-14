<?php

class mysqlHelper
{
    public $user   = "admin";
    public $pwd    = "123456";
    public $ip     = "192.168.0.1";
    public $dbname = "test";
    private $con   = null;

    public function connect($ip, $dbname, $user, $pwd)
    {
        if($ip == "" || $dbname == "" || $user == "" || $pwd == "")
            return false;
            
        $con = mysql_connect($ip, $user, $pwd);
        if(!$con)
            return false;

        $db = mysql_select_db($dbname, $con);
        if(!$db)
           return false;

        $this->ip     = $ip;
        $this->dbname = $dbname;
        $this->user   = $user;
        $this->pwd    = $pwd;
        $this->con    = $con;
        return true;
    }

    public function get($sqlString)
    {
        $result = mysql_query($sqlString, $this->con);
        $data = array();
        if($result && mysql_num_rows($result)>0)
        {
            while($row = mysql_fetch_assoc($result))
            {
                $data[] = $row;
            }
        }
        return $data;
    }
    
    public function insert($table, $data)
    {
        // $arr = array(
        // 'as_article_title'=>'数据库操作类',
        // 'as_article_author'=>'rex',
        // );
        // $res = $db->insert('as_article',$arr);

        $str  = '';
        $str .= "INSERT INTO `$table` ";
        $str .= "(`".implode("`,`",array_keys($data))."`) "; 
        $str .= " VALUES ";
        $str .= "('".implode("','",$data)."')";

        $res = mysql_query($str, $this->con);
        if($res && mysql_affected_rows()>0)
            return mysql_insert_id();
        else
            return false;
    }

    public function update($table, $data, $where)
    {
        // $arr = array(
        // 'as_article_title'=>'实例化对象',
        // 'as_article_author'=>'Lee',
        // );
        // $where = "as_article_id=1";
        // $res = $db->update('as_article',$arr,$where);

        $sql = 'UPDATE '.$table.' SET ';
        foreach($data as $key => $value)
        {
            $sql .= "{$key}='{$value}',";
        }
        $sql  = rtrim($sql,',');
        $sql .= " WHERE $where";
        $res  = mysql_query($sql,$this->con);
        if($res && mysql_affected_rows())
            return mysql_affected_rows();
        else
            return false;
    }

    public function del($table, $where)
    {
        $sql = "DELETE FROM `{$table}` WHERE {$where}";
        $res = mysql_query($sql, $this->con);
        if($res && mysql_affected_rows())
            return mysql_affected_rows();
        else
            return false;
    }



    public function getMax($table, $colname)
    {
        $sql  = "SELECT MAX({$colname}) from {$table}";
        $data = $this->get($sql);
        if(count($data) <= 0)
            return 0;
        return $data[0]["MAX({$colname})"] + 1;
    }

    public function getAllDiff($table, $colname)
    {
        $sql  = "SELECT DISTINCT {$colname} from {$table}";
        return $this->get($sql);
    }
}

?>