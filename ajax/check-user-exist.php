<?php
include('default.php');

$select = $conn_user->prepare("SELECT `uid` FROM `users` WHERE `email`= ? ");
$select->execute([$_POST['email']."@gmail.com"]);
if($select->rowCount()>0){
    $row = $select->fetch();
    $det = new accountDetails();
    $details = $det->accountData();

    $check = $conn_user->prepare("SELECT `invite_id` FROM `group_invite` WHERE `invite_user-id`= ? AND `group_id` = ?");
$check->execute([$row['uid'],$details['group_id']]);
if($check->rowCount()>0){
    $ans[0] = '2';
}else{

    $ans[0] = '1';
}
}else{
    $ans[0] = '0';
}
echo json_encode($ans);
?>