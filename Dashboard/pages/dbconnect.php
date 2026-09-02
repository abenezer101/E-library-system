<?php

$servername="localhost";
$uname="root";
$pass="";
$db="elibrary";

$conn=mysqli_connect($servername,$uname,$pass,$db);

if(!$conn){
    die("Connection Failed");
}

?>