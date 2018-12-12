<?php   

function __getGroupArray($section, $fileContent)
{
    if(trim($section) == '')
    {
        return $fileContent;
    }
    if(array_key_exists($section, $fileContent))
    {
        return $fileContent[$section];
    }
    return null;
}

function __addKey($section, $key, $value, $fileContent)
{
    $group = __getGroupArray($section, $fileContent);
    if($group !== null && array_key_exists($key, $group))
    {
        if(trim($section) == '')
            $fileContent[$key] = $value;
        else
            $fileContent[$section][$key] = $value;
        return $fileContent;
    }

    if(trim($section) == '')
        $fileContent[$key] = $value;
    else 
    {
        if($group !== null)
        {    
            $group[$key] = $value;
            $fileContent[$section] = $group;
        }
        else
        {
            $group=array($key=>$value);  
            $fileContent[$section] = $group;
        }
    }
    return $fileContent;
}

function getValue($section, $key, $default, $filepath)
{
    if(trim($filepath) == '' || trim($key) == '')
    {
        return $default;
    }

    if(!file_exists($filepath))
        return $default;

    $content = parse_ini_file($filepath, TRUE);
    $group   = __getGroupArray($section, $content);

    if($group !== null && array_key_exists($key, $group))
    {
        return $group[$key];
    }

    return $default;
}

function setValue($section, $key, $value, $filepath)
{
    if(trim($filepath) == '' || trim($key) == '')
        return FALSE;

    if(!file_exists($filepath))
    {
        $myfile = fopen($filepath, "w");
        if($myfile == NULL)
            return FALSE;
        fclose($myfile);
    }    

    $content = parse_ini_file($filepath, TRUE);
    $content = __addKey($section, $key, $value, $content);
    $writeBuf = "";
    //先写没有组的
    foreach ($content as $keyname=>$elem) 
    {
        if(!is_array($elem))
            $writeBuf .= $keyname."=".$elem."\r\n"; 
    }
    //再写有组的
    foreach ($content as $keyname=>$elem) 
    {
        if(is_array($elem))
        {
            $writeBuf .= "[".$keyname."]\r\n";
            foreach ($elem as $keyname2=>$elem2) 
                $writeBuf .= $keyname2."=".$elem2."\r\n"; 
        }
    }
    if (!$handle = fopen($filepath, 'w'))  
        return FALSE;  
    if (!fwrite($handle, $writeBuf))  
        return FALSE;  
    fclose($handle);  
    return TRUE;
}

?>