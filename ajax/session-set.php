<?php
session_start();
if(isset($_SESSION['back-to'][$_POST['href']])){
print json_encode(array('ans'=>1));
}else{
    print json_encode(array('ans'=>0));
}
$_SESSION['back-to'][$_POST['href']] = array('title'=>$_POST['title'],'count'=>$_POST['value'],'id'=>$_POST['id']);
?>