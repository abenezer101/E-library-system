<?php

if(isset($_GET['telegram_id'])){
$telegram_id=$_GET['telegram_id'];

include 'dbconnect.php';


$qry = "UPDATE users SET registration_status='1' WHERE telegram_id='$telegram_id'";
$result=mysqli_query($conn,$qry);

if($result){
    echo"User Approved";
    header('Location:viewuser.php');
}else{
    echo"ERROR!!";
}
}
?>