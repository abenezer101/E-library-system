<?php

if(isset($_GET['telegram_id'])){
$telegram_id=$_GET['telegram_id'];

include 'dbconnect.php';


$qry="delete from users where telegram_id=$telegram_id";
$result=mysqli_query($conn,$qry);

if($result){
    echo"DELETED";
    header('Location:deleteview.php');
}else{
    echo"ERROR!!";
}
}
?>