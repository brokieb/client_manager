<?php
include('default.php');
$select = $conn_form->prepare("SELECT * FROM `modal_group` WHERE `modal_id` = ? ");
$select->execute([$_POST['id']]);

if($select->rowCount() > 0 ){
    $row = $select->fetch(PDO::FETCH_ASSOC);

}
?>