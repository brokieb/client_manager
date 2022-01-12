<?php
require_once('../class.php');
if (!empty($_POST['username'])) {
    //Hidden form was filled in...
    //Suspected BOT
    //Do anything EXCEPT attempt register
    exit;
}
$rh = new configAndConnect();
if ($rh->issetCheck('THE_username') && $rh->issetCheck('THE_password') && $rh->issetCheck('THE_email')) {
    $register = new doRegisterAttempt($_POST['THE_username'], $_POST['THE_password'], $_POST['THE_email']);
    session_start();
    $alex = $register->attemptRegister();
    $_SESSION['alerts'][$uid = uniqid()] = Array(
            'add' => time(),
            'uid' => $uid,
            'type' => $alex['type'],
            'subject' => $alex['subject'],
            'value' => $alex['value']
        );
        if($alex['type']=="success"){
            ?>
<meta http-equiv="refresh" content="0;url=<?=configAndConnect::URL?>index.php?site=login"/> 
            <?php
        }else{
            ?>
<meta http-equiv="refresh" content="0;url=<?=configAndConnect::URL?>index.php?site=register"/> 
            <?php
        }
        ?>
        
     
     <?php
} else {
    $rh->outputString("None of Username, Password or Email can be empty");
}