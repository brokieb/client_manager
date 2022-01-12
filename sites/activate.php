<?php
$uid = uniqid();
if (isset($_GET['key'])) {
    $activate = new doRegisterAttempt('', '', '');
    $verify = $activate->verifyAccount($_GET['key']);
    if($verify){
        $ans = array(
            'add' => time(),
            'uid' => $uid,
            'type' => 'success',
            'subject' => 'Sukces!',
            'value' => "Twoje konto zostało poprawnie aktywowane, możesz swobodnie korzystać z aplikacji!"
        );
    }else{
        $ans = array(
            'add' => time(),
            'uid' => $uid,
            'type' => 'danger',
            'subject' => 'Błąd!',
            'value' => "Nieprawidłowy link :( Skontaktuj się z administratorem"
        );
    }

} else {//No key
    $ans = array(
        'add' => time(),
        'uid' => $uid,
        'type' => 'danger',
        'subject' => 'Błąd!',
        'value' => "Nieprawidłowy link :( Spróbuj ponownie"
    );
}
$_SESSION['alerts'][$uid] = $ans;
?>
<meta http-equiv="refresh" content="0;url=<?= configAndConnect::URL ?>index.php" />
