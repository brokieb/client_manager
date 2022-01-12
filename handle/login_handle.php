<?php
include("../functions.php");
require_once('../class.php');
if (!empty($_POST['username'])) {
    //Hidden form was filled in...
    //Do anything EXCEPT attempt login
    exit;
}
if(isset($_POST['THE_username'])){
$lh = new configAndConnect();
if ($lh->issetCheck('THE_username') && $lh->issetCheck('THE_password')) {
    $try_login = new doLoginAttempt($_POST['THE_username'], $_POST['THE_password']);
    if(isset($_GET['back'])){
        $back = "back=".$_GET['back']."&content=".$_GET['content'];
    }else{
        $back = "";
    }
    if(isset($_POST['THE_remember'])){
        $result = $try_login->attemptLogin('' . configAndConnect::URL . 'index.php?'.$back.' ',$_POST['THE_remember']);//Redirect to account page on success
    }else{
        $result = $try_login->attemptLogin('' . configAndConnect::URL . 'index.php?'.$back.' ');//Redirect to account page on success
    }
    session_start();
    $_SESSION['alerts'][$uid = uniqid()] = Array(
            'add' => time(),
            'uid' => $uid,
            'type' => $result['type'],
            'subject' => $result['subject'],
            'value' => $result['value']
        );
        if(isset($_GET['back'])){
          ?>
          <meta http-equiv="refresh" content="0;url=<?=configAndConnect::URL?>index.php?site=login&<?=$back?>"/> 
            <?php
            
        }else{
            ?>
<meta http-equiv="refresh" content="0;url=<?=configAndConnect::URL?>index.php?site=login"/> 
            <?php
        }
        ?>
        <?php
} else {
    $lh->outputString("Username and Password cannot be empty");
}
}elseif(isset($_GET['remember'])){
    // Przedłużenie sesji
    // Zalogowanie się$try_login = new doLoginAttempt($_POST['THE_username'], );
    $lh = new configAndConnect();
    $try_login = new doLoginAttempt();
    if(isset($_GET['back'])){
        $back = "back=".$_GET['back']."&content=".$_GET['content'];
    }else{
        $back = "";
    }
    $try_login-> cookieExtension($_COOKIE['remember'],'' . configAndConnect::URL . 'index.php?'.$back.' ');
?>
getttt
<?php 
}