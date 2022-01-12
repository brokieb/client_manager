<?php
$arr = array();
$site = explode("-", $_POST['000mode']);
$uid = uniqid();
print_r($_POST);
foreach ($_POST as $key => $value) {
    if (substr($key, 0, 3) != '000' && isset($value)) {
        if (is_array($value)) {

            $arr[$key] = implode(" | ", $value);
        } else {
            if ($value != null && $value != "0000") {
                $arr[$key] = $value;
            }
        }
    }
}
$arr['user_id'] = $_SESSION['user'];
$site = explode("-", $_POST['000mode']);
$columnString = implode('`,`', array_keys($arr));
$valueString = implode(',', array_fill(0, count($arr), ' ? '));
$capi = new GoogleCalendarApi();
switch ($site[0]) {
    case 'add':
        $sql = "INSERT INTO `{$site[1]}` (`{$columnString}`) VALUES ({$valueString})";
        $prepare = $conn_data->prepare($sql);
        $ans = array(
            'add' => time(),
            'uid' => $uid,
            'type' => 'success',
            'subject' => 'Sukces!',
            'value' => "Poprawnie dodano nową pozycję "
        );
        break;
    case 'edit':
        $fields = array();
        foreach ($arr as $key => $value) {
            $fields[] = '`' . $key . '`="' . $value . '" ';
        }
        $query_script = $conn_data->query("UPDATE  `{$site[1]}` SET " . implode(",", $fields) . " WHERE `user_id`={$_SESSION['user']} AND `{$site[1]}_id`={$_POST['000id']} ");
        $ans = array(
            'add' => time(),
            'uid' => $uid,
            'type' => 'success',
            'subject' => 'Sukces!',
            'value' => "Poprawnie edytowano pozycję"
        );
        $back = $site[1] . "-list";
        break;
    case 'preference':
        $select = $conn_data->query("SELECT * FROM `preference_user` WHERE `preference_id`='{$site[1]}' AND `user_id`='{$_SESSION['user']}' ");
        $select->execute();
        $row_check = $select->fetch();
        if (empty($row_check)) {
            $query_script = $conn_data->query("INSERT INTO `preference_user`(`preference_id`,`preference_value`,`user_id`) VALUES (
            '{$site[1]}',
            '" . implode(" | ", $_POST['pref-value']) . "',
            '{$_SESSION['user']}'
            ) ");


            $ans = array(
                'add' => time(),
                'uid' => $uid,
                'type' => 'success',
                'subject' => 'Sukces!',
                'value' => "Poprawnie dodano nową preferencję"
            );
        } else {
            if (is_array($_POST['pref-value'])) {
                $query_script = $conn_data->query("UPDATE `preference_user` SET 
                `preference_value`='" . implode(" | ", $_POST['pref-value']) . "'
                WHERE `preference_id`={$row_check['preference_id']} AND `user_id`='{$_SESSION['user']}' ");
            }

            $ans = array(
                'add' => time(),
                'uid' => $uid,
                'type' => 'success',
                'subject' => 'Sukces!',
                'value' => "Poprawnie zaktualizowano tą opcje"
            );
        }

        $back = $_GET['site'];

        break;
    case 'google':

        switch ($site[1]) {
            case 'delete':

                $query_script = $conn_user->query("UPDATE `users` SET `google_api`=NULL WHERE `uid`={$_SESSION['user']} ");
                $ans = array(
                    'add' => time(),
                    'uid' => $uid,
                    'type' => 'success',
                    'subject' => 'Sukces!',
                    'value' => "Poprawnie usunięto połączenie z google Calendar"
                );
                $back = 'profile/calendar-sync';

                break;
            case 'sync':

                $select = $conn_data->query("SELECT `meet_sync`,`meet_id`,`meet_comment`,`meet_build-address`,`meet_client-name`,`meet_type`,`meet_date`,`meet_time`,DATE_FORMAT(date_add(CONCAT(`meet_date`,'T',`meet_time`), INTERVAL 1 hour),'%Y-%m-%dT%H:%i:%s') as 'koniec' FROM `meet` WHERE `user_id`={$_SESSION['user']} AND `meet_date`>='" . Date("Y-m-d") . "' ");
                $i = 0;
                $update = "";
                while ($row = $select->fetch()) {
                    $data = $capi->GetRefreshedAccessToken($details['google_api']);
                    $access_token = $data['access_token'];
                    $temes = $capi->CheckSynced($details['google_calendar-default'], $row['meet_sync'], $access_token);
                    if ($temes == 0) {


                        $data = $capi->GetRefreshedAccessToken($details['google_api']);
                        $access_token = $data['access_token'];
                        $user_timezone = $capi->GetUserCalendarTimezone($access_token);
                        $event = array(
                            'summary' => $row['meet_type'] . " " . $row['meet_client-name'],
                            'start' => array(
                                'dateTime' => $row['meet_date'] . 'T' . $row['meet_time'] . ':00',
                                'timeZone' => $user_timezone
                            ),
                            'end' => array(
                                'dateTime' => $row['koniec'],
                                'timeZone' => $user_timezone
                            ),
                            'location' => $row['meet_build-address'],
                            'description' => "{$row['meet_comment']} </br></br><a href='https://cmanager.pl/index.php?site=meet-list&content={$row['meet_id']}'> SZCZEGÓŁY</a>"
                        );
                        $data = $capi->GetRefreshedAccessToken($details['google_api']);
                        $access_token = $data['access_token'];
                        $event_id = $capi->CreateCalendarEvent($details['google_calendar-default'], $event, $access_token);
                        if ($conn_data->query("UPDATE `meet` SET `meet_sync`='{$event_id}' WHERE `meet_id`='{$row['meet_id']}' AND `user_id`={$_SESSION['user']};")) {
                            $i++;
                        }
                    }
                }
                $ans = array(
                    'add' => time(),
                    'uid' => $uid,
                    'type' => 'success',
                    'subject' => 'Sukces!',
                    'value' => "Poprawnie dodano wydarzenia do kalendarza (" . $i . ")"
                );
                $db = $conn_data;
                $back = 'profile/calendar-sync';
                break;
            case 'removeMeets':
                $select = $conn_data->query("SELECT `meet_sync`,`meet_id` FROM `meet` WHERE `user_id` = {$_SESSION['user']} AND `meet_sync` IS NOT NULL");
                if($select->rowCount() > 0 ){
                    $ax = 0;
                    while($row = $select->fetch()){
                        $data = $capi->GetRefreshedAccessToken($details['google_api']);
                        $access_token = $data['access_token'];
                        $event_id = $capi->RemoveCalendarEvent($details['google_calendar-default'], $row['meet_sync'], $access_token);
                        $update = $conn_data->query("UPDATE `meet` SET `meet_sync` = NULL WHERE `meet_id` = {$row['meet_id']} AND `user_id` = {$_SESSION['user']}");
                        $ax++;
                    }
                    $ans = array(
                        'add' => time(),
                        'uid' => $uid,
                        'type' => 'success',
                        'subject' => 'Sukces!',
                        'value' => "Poprawnie usunięto wydarzenia z kalendarza (" . $ax . ")"
                    );
                }else{
                    $ans = array(
                        'add' => time(),
                        'uid' => $uid,
                        'type' => 'danger',
                        'subject' => 'Sukces!',
                        'value' => "Wystąpił błąd podczas usuwania, możliwym problem jest to że nie masz żadnych spotkań zsynchronizowanych z naszym programem. Proszę o kontakt z administratorem w celu usunięcia spotkań"
                    );
                }
                $back = 'profile/calendar-sync';
                break;
            case 'set_default':
                $select = $conn_user->prepare("UPDATE `users` SET `google_calendar-default`= ? WHERE `uid`={$_SESSION['user']};  ");
                $select->execute([$_POST['calendar_id']]);
                $ans = array(
                    'add' => time(),
                    'uid' => $uid,
                    'type' => 'success',
                    'subject' => 'Sukces!',
                    'value' => "Poprawnie ustawiono domyślny kalendarz"
                );
                $db = $conn_user;
                $back = 'profile/calendar-sync';
                break;
        }

        break;
    case 'resetpw':
        switch ($site[1]) {
            case 'ask':

                $lh = new configAndConnect();
                $try_login = new doResetAttempt($_POST['THE_email'], '');
                $result = $try_login->attemptReset(); //Redirect to account page on success
                $ans = array(
                    'add' => time(),
                    'uid' => $uid,
                    'type' => 'success',
                    'subject' => 'Sukces!',
                    'value' => "Jeżeli email istnieje w bazie wysłaliśmy stosownego maila z instrukcjami"
                );


                break;
            case 'set':

                $activate = new doResetAttempt('', '');
                $verify = $activate->verifyAccount($_POST['key'], $_POST['THE_password']);
                if ($verify) {
                    $ans = array(
                        'add' => time(),
                        'uid' => $uid,
                        'type' => 'success',
                        'subject' => 'Sukces!',
                        'value' => "Poprawnie zmieniono hasło, możesz się zalogować nowymi danymi :)"
                    );
                } else {
                    $ans = array(
                        'add' => time(),
                        'uid' => $uid,
                        'type' => 'success',
                        'subject' => 'Sukces!',
                        'value' => "Nieokreślony błąd przy zmianie hasła, skontaktuj się z administratorem"
                    );
                }



                break;
        }
        $db  = '0';
        $back = 'login';
        break;
    case "move";
        $sitte = explode("-", $_POST['page']);
        $select = $conn_data->prepare("UPDATE {$sitte[1]} SET `row_status` = ? WHERE `{$sitte[1]}_id` = ? AND `user_id` = ?");
        $select->execute([$_POST['status'], $_POST['id'], $_SESSION['user']]);
        if ($select->rowCount() > 0) {
            switch ($_POST['status']) {
                case '0':
                    $ans = array(
                        'add' => time(),
                        'uid' => $uid,
                        'type' => 'success',
                        'subject' => 'Sukces!',
                        'value' => "Pozycja została poprawnie bezpowrotnie usunięta"
                    );

                    break;
                case '1':
                    $ans = array(
                        'add' => time(),
                        'uid' => $uid,
                        'type' => 'success',
                        'subject' => 'Sukces!',
                        'value' => "Pomyślnie oznaczono pozycje jako nierozwiązaną i umieszczono w archiwum"
                    );

                    break;
                case '2':
                    $ans = array(
                        'add' => time(),
                        'uid' => $uid,
                        'type' => 'success',
                        'subject' => 'Sukces!',
                        'value' => "Pomyślnie umieszczono zakończoną pozycję w archiwum"
                    );

                    break;
            }
            $back = $_GET['site'];
        } else {
            $ans = array(
                'add' => time(),
                'uid' => $uid,
                'type' => 'danger',
                'subject' => 'Błąd!',
                'value' => "Nie udało się wykonać zaplanowanej akcji, możliwy problem to niewystarczające uprawnienia"
            );
        }

        break;
    case "company":

        switch ($site[1]) {
            case 'addUser':

                foreach ($_POST['email'] as $z) {
                    $reg = new doRegisterAttempt(null, null, $z);
                    $reg->attemptRegisterNull();
                }

                $ans = array(
                    'add' => time(),
                    'uid' => $uid,
                    'type' => 'success',
                    'subject' => 'W porządku!',
                    'value' => "Konta zostały utworzone lub zostały wysłane odpowiednie wiadomości do użytkowników"
                );

                break;
            case 'join':

                $group_inv = $conn_user->prepare("SELECT * FROM `group_invite` WHERE `invite_id` = ? AND `invite_user-id` = ?");
                $group_inv->execute([$_POST['id'], $_SESSION['user']]);
                if ($group_inv->rowCount() > 0) {
                    $row = $group_inv->fetch();
                    $group_join = $conn_user->prepare("UPDATE `users` SET `group_id`= ?  WHERE `uid` = ?");
                    if ($group_join->execute([$row['group_id'], $_SESSION['user']])) {
                        $inv_del = $conn_user->prepare("DELETE FROM  `group_invite` WHERE `invite_id` = ? AND `invite_user-id` = ?");
                        $inv_del->execute([$_POST['id'], $_SESSION['user']]);
                        $ans = array(
                            'add' => time(),
                            'uid' => $uid,
                            'type' => 'success',
                            'subject' => 'Sukces',
                            'value' => "Twoje konto zostało pomyślnie powiązane z grupą "
                        );
                    }
                } else {
                    $ans = array(
                        'add' => time(),
                        'uid' => $uid,
                        'type' => 'danger',
                        'subject' => 'Wystąpił błąd!',
                        'value' => "Nie udało się dołączyć do grupy, skontaktuj się z administratorem"
                    );
                }
                break;
        }
        $back = $_GET['site'];
        break;
}
if (isset($query_script)) {
    if ($details['google_api'] != NULL && $_POST['000mode'] == 'add-meet') {

        $last_id = $conn_data->lastInsertId();
        $data = $capi->GetRefreshedAccessToken($details['google_api']);
        $access_token = $data['access_token'];

        $user_timezone = $capi->GetUserCalendarTimezone($access_token);

        if (isset($arr['meet_client-name'])) {

            $summary = $arr['meet_type'] . " " . $arr['meet_client-name'];
        } else {

            $summary = $arr['meet_type'] . " " . $arr['meet_client-id'];
        }

        if (isset($arr['meet_build-address'])) {
            $location = $arr['meet_build-address'];
        } else {
            $location = $arr['meet_build-id'];
        }

        if (isset($arr['meet_comment'])) {
            $comment = $arr['meet_comment'] . "</br></br><a href='https://cmanager.pl/index.php?site=meet-list&content={$last_id}'> SZCZEGÓŁY</a>";
        } else {
            $comment = "</br></br><a href='https://cmanager.pl/index.php?site=meet-list&content={$last_id}'> SZCZEGÓŁY</a>";
        }

        $event = array(
            'summary' => $summary,
            'start' => array(
                'dateTime' => $arr['meet_date'] . 'T' . $arr['meet_time'] . ':00',
                'timeZone' => $user_timezone
            ),
            'end' => array(
                'dateTime' => date("Y-m-d\TH:i:s", strtotime($arr['meet_date'] . 'T' . $arr['meet_time'] . ':00') + 3600),
                'timeZone' => $user_timezone
            ),
            'location' => $location,
            'description' => $comment
        );

        $event_id = $capi->CreateCalendarEvent($details['google_calendar-default'], $event, $access_token);
    } else {
    }
    $_SESSION['alerts'][$uid] = $ans;

    if (isset($back)) {
?>
        <meta http-equiv="refresh" content="0;url=<?= configAndConnect::URL ?>index.php?site=<?= $back ?>" />
    <?php
    } else {
    ?>
        <meta http-equiv="refresh" content="0;url=<?= configAndConnect::URL ?>index.php?site=<?= $site[1] ?>-list" />
    <?php
    }

    ?>

    <?php
} elseif (isset($prepare)) {
    if ($prepare->execute(array_values($arr))) {
        if ($details['google_api'] != NULL && $_POST['000mode'] == 'add-meet') {
            $last_id = $conn_data->lastInsertId();
            $data = $capi->GetRefreshedAccessToken($details['google_api']);
            $access_token = $data['access_token'];
            $user_timezone = $capi->GetUserCalendarTimezone($access_token);

            if (isset($arr['meet_client-name'])) {
                $summary = $arr['meet_type'] . " " . $arr['meet_client-name'];
            } else {
                $summary = $arr['meet_type'] . " " . $arr['meet_client-id'];
            }
            if (isset($arr['meet_build-address'])) {
                $location = $arr['meet_build-address'];
            } else {
                $location = $arr['meet_build-id'];
            }
            if (isset($arr['meet_comment'])) {
                $comment = $arr['meet_comment'] . "</br></br><a href='https://cmanager.pl/index.php?site=meet-list&content={$last_id}'> SZCZEGÓŁY</a>";
            } else {
                $comment = "</br></br><a href='https://cmanager.pl/index.php?site=meet-list&content={$last_id}'> SZCZEGÓŁY</a>";
            }
            $event = array(
                'summary' => $summary,
                'start' => array(
                    'dateTime' => $arr['meet_date'] . 'T' . $arr['meet_time'] . ':00',
                    'timeZone' => $user_timezone
                ),
                'end' => array(
                    'dateTime' => date("Y-m-d\TH:i:s", strtotime($arr['meet_date'] . 'T' . $arr['meet_time'] . ':00') + 3600),
                    'timeZone' => $user_timezone
                ),
                'location' => $location,
                'description' => $comment
            );
            $event_id = $capi->CreateCalendarEvent($details['google_calendar-default'], $event, $access_token);

            $insert_event = $conn_data->query("UPDATE `meet` SET `meet_sync` = '{$event_id}' WHERE `meet_id` = {$last_id}");
        }
        $_SESSION['alerts'][$uid] = $ans;
        if (isset($back)) {
    ?>
            <meta http-equiv="refresh" content="0;url=<?= configAndConnect::URL ?>index.php?site=<?= $back ?>" />
        <?php
        } else {
        ?>
            <meta http-equiv="refresh" content="0;url=<?= configAndConnect::URL ?>index.php?site=<?= $site[1] ?>-list" />
        <?php
        }
    }
} else {
    $_SESSION['alerts'][$uid] = $ans;

    if (isset($back)) {
        ?>
        <meta http-equiv="refresh" content="0;url=<?= configAndConnect::URL ?>index.php?site=<?= $back ?>" />
    <?php
    } else {
    ?>
        <meta http-equiv="refresh" content="0;url=<?= configAndConnect::URL ?>index.php?site=<?= $site[1] ?>-list" />
<?php
    }
}
?>