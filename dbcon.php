<?php
$host="127.0.0.1";
$user="root";
$password="";
$dbname="job_portal";
try{
$conn=new mysqli($host,$user,$password,$dbname);
if($conn)
{
   //  echo "connection sucessful";
}
}
catch(Exception $e)
{
    echo $e->getMessage();
}
?>