<?php
require_once('../class.php');
require_once('../conn.php');
$connect = new ConfigAndConnect;
$conn_user = $connect->db_connect('user');
// $event = array('title' => 'Spotkanie z aplikacji :)', 'event_time' => Array ( 'start_time' => '2021-06-16T15:12:00', 'end_time' => '2021-06-16T16:12:00', 'event_date' => ''), 'all_day' => 0 );


session_start();
if(isset($_GET['code'])) {
    $capi = new GoogleCalendarApi();
    $data = $capi->GetAccessToken($_GET['code']);
    $select = $conn_user->prepare("UPDATE `users` SET `google_api`= ? WHERE `uid`=? ");
    $uid = uniqid();
        if($select->execute([$data['refresh_token'],$_SESSION['user']])){
       $ans = array(
        'add' => time(),
        'uid' => $uid,
        'type' => 'success', 
        'subject' => 'Sukces!',
        'value' => "Poprawnie połączono konto z google"
      );
    }else{
      $ans = array(
        'add' => time(),
        'uid' => $uid,
        'type' => 'danger',
        'subject' => 'Błąd!',
        'value' => "Błąd przy połączeniu z kontem google. Spróbuj ponownie lub skontaktuj się z administratorem"
    );
    }
    $_SESSION['alerts'][$uid] = $ans;
    // header('location: https://cmanager.pl/index.php?site=profile/calendar-sync');

}
