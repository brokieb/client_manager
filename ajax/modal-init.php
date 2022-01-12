<?php
include('default.php');
$select = $conn_form->prepare("SELECT * FROM `modal` WHERE `modal_id` = ? ");
$select->execute([$_POST['modalId']]);

if($select->rowCount() > 0 ){
    $row = $select->fetch(PDO::FETCH_ASSOC);
    $ans = array();
    $select = $conn_form->query("SELECT * FROM `modal_group` WHERE `modal_id` = {$row['modal_id']} AND `group_content` LIKE '%".$_POST['content']."%' ");
    if($select->rowCount() > 0 ){
        while($row2 = $select->fetch(PDO::FETCH_ASSOC)){
            $ans['group'][] = $row2;
        }
    }else{
        $ans['group'] = null;
    }
    $ans['modal'][] = $row;
}else{

    $ans['modal'][] = "SELECT * FROM `modal` WHERE `modal_id` = {$_POST['modalId']}";
}
echo json_encode($ans);
?>