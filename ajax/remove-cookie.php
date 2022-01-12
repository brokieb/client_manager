<?php
$arr = json_decode($_COOKIE['alert']);
$ans = [];
foreach($arr as $z){
    if($z!=$_POST['id']){
        $ans[] = $z;
    }
}

setcookie('alertX',json_encode($ans),0,"/");
echo json_encode(1);
?>