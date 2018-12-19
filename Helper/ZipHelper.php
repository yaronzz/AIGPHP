<?php
require_once 'pathHelper.php';
function zipFile($filePath, $toPath)
{
    $zip = new ZipArchive();
    if($zip->open($toPath,ZipArchive::OVERWRITE))
    {               
        $zip->addFile($filePath, basename($filePath));           
        $zip->close(); 
        return TRUE;                                 
    }   
    return FALSE;                                 
}        

function zipFiles($filePathList, $toPath)
{
    $zip = new ZipArchive();
    if($zip->open($toPath,ZipArchive::OVERWRITE))
    {          
        foreach($filePathList as $file)
            $zip->addFile($file,basename($file));
        $zip->close();      
        return TRUE;                                 
    }                                   
    return FALSE;          
}

function zipPath($dirPath, $toPath, $zip=NULL)
{   
    if($zip == NULL)
    {
        $zip = new ZipArchive();
        if($zip->open($toPath, ZipArchive::OVERWRITE) == FALSE)
            return FALSE;          
    }

    $handler = opendir($dirPath); 
    while(($filename = readdir($handler)) !== false)
    {
        if($filename != "." && $filename != "..")
        {
            if(is_dir($dirPath."/".$filename))
                addFileToZip($dirPath."/".$filename,  $toPath, $zip);
            else 
                $zip->addFile($dirPath."/".$filename);
        }
    }
    closedir($path);
    $zip->close(); 
    return TRUE;                                 
}

function unzip($filePath, $toPath)
{
    $zip=new ZipArchive();
    if($zip->open($filePath))
    { 
        if(mkdirs($toPath) == FALSE)
            return FALSE;
        $zip->extractTo($toPath);
        $zip->close();
        return TRUE;                                 
    }
    return FALSE;
}
?>