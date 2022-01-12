<?php
include('../default.php');
switch ($_POST['content']) {
    case 'build':
        $content_reverse = "client";
        break;
    case 'client':
        $content_reverse = "build";
        break;
}

switch ($_POST['matchType']) {
    case '0': //odrzucone
        $comment = "Poprawnie odrzucono pozycje!";
        break;
    case '1': //zaakceptowane
        $comment = "Poprawnie zaakceptowano propozycje!";
        break;
    default:
        throw new Exception('Nieobsługiwany kierunek');
        exit();
        break;
}
    $insert = $conn_data->prepare("INSERT INTO `matches` (`build_id`,`client_id`,`user_id`,`match_direction`) values (? , ? , ? , ?) ");
    if($insert->execute([$_POST['mainId'],$_POST['foreignId'],$_SESSION['user'],$_POST['matchType']])){
        echo json_encode($comment);
    } else {
        echo json_encode("0");
    }

//cho $sql = "UPDATE {$content_reverse} SET {$match_way_foreign}=  ?  WHERE `user_id`={$_SESSION['user']} AND `{$content_reverse}_id`= ? ";
// echo "</br></br></br>";
// echo $sql_foreign =  "UPDATE {$_POST['content']} SET {$match_way}=  ?  WHERE `user_id`={$_SESSION['user']} AND `{$_POST['content']}_id`= ? ";
//     $insert  = $conn_data->prepare($sql);
//     $insert_foreign  = $conn_data->prepare($sql_foreign);

//     if ($insert_foreign->execute([$ans, $_POST['mainId']]) && $insert->execute([$ans_foreign, $_POST['foreignId']])) {
    
// }
