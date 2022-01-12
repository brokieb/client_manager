<?php
include('default.php');
$sql = "SELECT * FROM `building` WHERE `user_id`=".$_SESSION['user']." AND";
$query = mysqli_query($conn,$sql);
$row = mysqli_fetch_array($query,MYSQLI_ASSOC);
header('location:index.php')
?>