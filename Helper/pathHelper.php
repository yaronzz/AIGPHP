<?php

function mkdirs($path)
{
    if(trim($path) == '')
        return FALSE;

    $path = str_replace('\\','/',$path);
    if(is_dir($path))
        return TRUE;
    
    $str = strrchr($path, '/');
    $len = strlen($str);
    if($len > 0)
    {
        $cutlen = strlen($path) - $len;
        $subpath = substr($path, 0, $cutlen);
        mkdirs($subpath);
    }

    mkdir($path);
    return TRUE;
}

function replaceLimitChar($path, $newChar)
{
    if(trim($path) == '')
        return '';

    $path = str_replace(':',$newChar,$path);
    $path = str_replace('/',$newChar,$path);
    $path = str_replace('?',$newChar,$path);
    $path = str_replace('<',$newChar,$path);
    $path = str_replace('>',$newChar,$path);
    $path = str_replace('|',$newChar,$path);
    $path = str_replace('\\',$newChar,$path);
    $path = str_replace('*',$newChar,$path);
    $path = str_replace('\"',$newChar,$path);
    return $path;
}

function getObjsInDir($path, $all0fils1dirs2=0)
{   
    if(trim($path) == '' || !is_dir($path))
        return null;

    $array = array();
    if ($dh = opendir($path))
    {
        while (($file = readdir($dh))!= false)
        {
            $filePath = $path.$file;
            $flag = is_dir($filePath);
            $tmp  = strrchr($filePath,"RECYCLE.BIN");

            if($tmp != '')
                continue;
            if($all0fils1dir2 == 1 && $flag)
                continue;
            if($all0fils1dir2 == 2 && !$flag)
                continue;  
            array_push($array, $filePath);
        }
    }
    return $array;
}

?>