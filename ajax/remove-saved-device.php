<?php
include('default.php');
$remove = $conn_user->prepare("DELETE FROM `saved_device` WHERE `save_id`= ?");
if($remove->execute([$_POST['id']])){
    echo json_encode(1);
}

?>