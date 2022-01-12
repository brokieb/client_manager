<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once('../class.php');
if (!empty($_POST['username'])) {
    //Hidden form was filled in...
    //Do anything EXCEPT attempt login
    exit;
}
$lh = new configAndConnect();
if ($lh->issetCheck('THE_email')) {
    $try_login = new doResetAttempt($_POST['THE_email'],'');
    $result = $try_login->attemptReset();//Redirect to account page on success
   
    
        ?>
<meta http-equiv="refresh" content="0;url=<?=configAndConnect::URL?>index.php?site=login"/> 
        <?php
} else {
    $lh->outputString("Username and Password cannot be empty");
}