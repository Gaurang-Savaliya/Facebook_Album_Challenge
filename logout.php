<?php
require_once 'config.php';

$txt = "|Logout - ".$_SESSION['usr']." |";
$myfile = file_put_contents('temp/logs.txt', $txt.PHP_EOL , FILE_APPEND | LOCK_EX);

if(is_file('temp/albumall/'.$_SESSION['usr'].'/all_'.$_SESSION['usr'].'.zip'))
{
    unlink('temp/albumall/'.$_SESSION['usr'].'/all_'.$_SESSION['usr'].'.zip');
    rmdir('temp/albumall/'.$_SESSION['usr']);
}

if(file_exists('temp/albumns/'.$_SESSION['usr']))
{
foreach(glob("temp/albumns/".$_SESSION['usr']."/*") as $file)
    {
        if(is_dir($file)) { 
            recursiveRemoveDirectory($file);
        } else {
            unlink($file);
        }
    }
rmdir('temp/albumns/'.$_SESSION['usr']);
}
unset($_SESSION['fb_access_token']);

session_destroy();
header('Location:https://gaurangsavaliya4.000webhostapp.com/index.php');
?>