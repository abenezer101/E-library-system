<?php

if(isset($_GET['id'])){
$id=$_GET['id'];

include 'dbconnect.php';


$qry="delete from books where id=$id";
$result=mysqli_query($conn,$qry);

if($result){
    echo"DELETED";
    header('Location:deletebook.php');
}else{
    echo"ERROR!!";
}
}
?>