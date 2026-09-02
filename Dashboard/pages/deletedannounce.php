<?php

if(isset($_GET['id'])){
$id=$_GET['id'];

include 'dbconnect.php';


$qry="delete from announcement where id=$id";
$result=mysqli_query($conn,$qry);

if($result){
    echo"DELETED";
    header('Location:deleteannouncement.php');
}else{
    echo"ERROR!!";
}
}
?>