<?php

if(isset($_GET['user_id'])){
$user_id=$_GET['user_id'];

include 'dbconnect.php';


$qry="delete from issued_books where user_id=$user_id";
$result=mysqli_query($conn,$qry);

if($result){
    echo"DELETED";
    header('Location:editissuedBook.php');
}else{
    echo"ERROR!!";
}
}
?>