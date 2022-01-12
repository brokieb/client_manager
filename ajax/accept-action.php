<?php
include('default.php');
$site = explode("/",$_POST['site']);
$table = explode("-",$site[1]);
switch($_POST['action']){
    case 'archive-this':
                  if(mysqli_query($conn,$sql)){
    echo json_encode(array('title' => 'OK! ','value' => 'Pozycja poprawnie zabezpieczona w bazie archiwum','type' => 'success'));
}else{
echo json_encode(array('title' => 'Błąd! ','value' => 'Pozycja uległa uszkodzeniu po drodze ','type' => 'danger'));
}
        break;
        case 'remove-this':
            $sql = "DELETE FROM `".$table[1]."` WHERE `user_id`='".$_SESSION['user']."' AND  `".$table[1]."_id`='".$_POST['id']."' ";
            if(mysqli_query($conn,$sql)){
    echo json_encode(array('title' => 'OK! ','value' => 'Pozycja została poprawnie usunięta, zmiany będą widoczne po odświeżeniu strony','type' => 'success'));
}else{
echo json_encode(array('title' => 'Błąd! ','value' => 'Nie udało się usunąć pozycji ','type' => 'danger'));
}
        break;
}

