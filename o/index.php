<?php
require_once('../class.php');
if (isset($_GET['key'])) {
    ?>
<form method='POST'>
<input type='password' name='password'>
<input type='hidden' name='key' value='<?=$_GET['key']?>'>
<button type='submit'>ZMIANA</button>
</form>
    <?php
    
} else {//No key
    $cc = new configAndConnect();
    $cc->outputString("Key is required");
}

if(isset($_POST['key'])){
    $activate = new doResetAttempt('', '');
    $verify = $activate->verifyAccount($_POST['key'],$_POST['password']);
    ($verify) ? $activate->outputString("Account activated") : $activate->outputString("Key is invalid");
}else
?>
