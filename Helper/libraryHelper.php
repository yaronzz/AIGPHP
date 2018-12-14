<?php
require_once 'mysqlHelper.php';
/****'book'TABLE****************************
 id   name   author   date   desc   type   path   popular   doubanurl
********************************************/
class libraryHelper
{
    private $conn = null;
    public function connect($ip, $dbname, $user, $pwd)
    {
        $con = new mysqlHelper();
        $ret = $con->connect($ip, $dbname, $user, $pwd);
        if(!$ret)
            return false;
        $this->conn = $con;
        return true;
    }

    private function getNewID()
    {
        $sql  = "select max(id) from book";
        $data = $this->conn->get($sql);
        if(count($data) <= 0)
            return 0;
        return $data[0]['max(id)'] + 1;
    }

    public function getBookCount()
    {
        $sql  = "select * from `book`";
        $data = $this->conn->get($sql);
        return count($data);
    }

    public function findByName($name, $exactMatch=false)
    {
        $sql  = "select * from `book` where name like'%$name%'";
        if($exactMatch)
            $sql  = "select * from `book` where name like'$name'";

        $data = $this->conn->get($sql);
        if(count($data) <= 0)
            return null;
        return $data;
    }

    public function findByAuthor($author, $exactMatch=false)
    {
        $sql  = "select * from `book` where author like'%$author%'";
        if($exactMatch)
            $sql  = "select * from `book` where author like'$author'";

        $data = $this->conn->get($sql);
        if(count($data) <= 0)
            return null;
        return $data;
    }

    public function findByType($type)
    {
        $sql  = "select * from `book` where type like'$type'";
        $data = $this->conn->get($sql);
        if(count($data) <= 0)
            return null;
        return $data;
    }

    public function add($attr)
    {
        if(!array_key_exists('name', $attr))
            return false;
        if(!array_key_exists('author', $attr))
            return false;
        if(!array_key_exists('type', $attr))
            return false;
        if(!array_key_exists('path', $attr))
            return false;

        if($this->findByName($attr['name'],false) !== null)
            return false;

        $id = $this->getNewID();
        $attr['id'] = $id;
        return $this->conn->insert('book', $attr);
    }

    public function changeName($oldname, $newname)
    {
        $attr = array(
        'name'=>$newname,
        );
        $where = "name='$oldname'";
        return $this->conn->update('book', $attr, $where);
    }

    public function getBooks($startIndex = 0, $endIndex = 100)
    {
        if($startIndex > $endIndex || $startIndex < 0 || $endIndex < 0)
            return null;
        
        if($startIndex == 0)
            $sql = "select * from book limit $endIndex";
        else    
        {
            $from = $startIndex - 1;
            $to = $endIndex - $startIndex + 1;
            $sql = "select * from book limit $from,$to";
        }
        $data = $this->conn->get($sql);
        return $data;
    }

    public function getAllType()
    {
        $data = $this->conn->getAlldiff("book", "type");  
        return $data;
    }

    public function getAllAuthor()
    {
        $data = $this->conn->getAlldiff("book", "author");  
        return $data;
    }
}

?>