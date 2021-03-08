<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function Connection(){
$server="db";
$user="root";
$pass="notsecure112";
$db="niceHash";

$connection = mysqli_connect($server,$user,$pass,$db);
if (mysqli_connect_errno()) 
{
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
else
{
        //echo "Worked";
}

return $connection;
}
?>
