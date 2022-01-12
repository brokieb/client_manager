<?php

/*
 * Config values and database connection
 */

include 'conn.php';

/*
 * Handle registering, detail sanitization and activation email sending
 */

class doRegisterAttempt extends configAndConnect
{
    private $username;
    private $stated_password;
    public string $email;
    private int $uid;
    public string $key;
    protected PDO $db;

    public function __construct($username, $password, string $email)
    {

        $this->username = $username;

        if ($password == null) {
            $this->stated_password = uniqid();
        } else {
            $this->stated_password = $password;
        }

        $this->email = $email;
        $this->db = (new configAndConnect)->db_connect('user');
    }
    public function attemptRegister(): array
    {
        $validate = $this->validateUsername();
        if ($validate == 1 && $this->validatePassword()) {
            $this->insertAccount();
            if (configAndConnect::REQUIRE_EMAIL_ACTIVATION) {

                return $this->sendActivateLink();
            } else {
                $this->manualActivateAccount();
                return array('type' => 'success', 'subject' => 'Sukces', 'value' => "Konto poprawnie założone, możesz się teraz zalogować");
            }
        } elseif ($validate == 2) {
            return array('type' => 'danger', 'subject' => 'Błąd', 'value' => "Użytkownik o takich danych już istnieje, wykorzystaj inne");
        } elseif ($validate == 3) {
            return array('type' => 'danger', 'subject' => 'Błąd', 'value' => "{$validate} Login musi się składać od 3 do 24 znaków");
        } else {
            return array('type' => 'danger', 'subject' => 'Błąd', 'value' => "Dziwny błąd, nie udało się utworzyć konta :(");
        }
    }
    public function attemptRegisterNull()
    {

        if ($this->validateEmail()) { //email poprawny
            $uname = $this->validateUsername();
            if ($uname == 1) {
                $this->generateUsername();
                $this->stated_password = uniqid();
                $this->insertAccount();
                $this->manualJoinGroup();
                $this->manualActivateAccount();
                $data['login'] = $this->username;
                $data['password'] = $this->stated_password;
                $this->sendEmail('new-account', $data);
                return true;
            } elseif ($uname == 2) { //konto takie istnieje bondziorno, wysłać zaproszenie
                $this->manualInviteToGroup();
            } else { //coś innego

            }
        } else { //błąd walidacji, błędny adres email

        }
    }

    protected function validateUsername(): int
    {
        if ((strlen($this->username) >= configAndConnect::MIN_USERNAME_LENGTH && strlen($this->username) <= configAndConnect::MAX_USERNAME_LENGTH) || $this->username == null) {
            $select = $this->db->prepare("SELECT `uid` FROM `users` WHERE `username` = ? OR `email` = ? LIMIT 1;");
            $select->execute([$this->username, $this->email]);
            if ($select->rowCount() > 0) { //Row found for username
                //Username already exists
                return 2;
            } else {
                return 1;
                //Can use username
            }
        } else {
            return 3;
        }
    }
    protected function generateUsername()
    {
        $Name = explode("@", $this->email);
        $tryName = $Name[0] . rand(0, 999);
        $this->username  = $tryName;
        // do{
        // }while($this->validateUsername()==1);
    }


    protected function validatePassword(): bool
    {
        if (strlen($this->stated_password) >= configAndConnect::MIN_PASSWORD_LENGTH && strlen($this->stated_password) <= configAndConnect::MAX_PASSWORD_LENGTH) {
            return true; //Password suits min and max length
        } else {
            return false;
        }
    }

    protected function validateEmail(int $max_length = 60): bool
    {
        if (filter_var($this->email, FILTER_VALIDATE_EMAIL) && strlen($this->email) <= $max_length) {
            return true; //Valid email address
        } else {
            return false;
        }
    }

    protected function insertAccount(): void
    {
        $hashed_password = password_hash($this->stated_password, PASSWORD_DEFAULT); //Hash the submitted password
        $insert = $this->db->prepare("INSERT IGNORE INTO `users` (`username`, `password`, `email`) VALUES (?,?,?)");
        $insert->execute([$this->username, $hashed_password, $this->email]); //Create the user you defined in the form
        $this->uid = $this->db->lastInsertId();
    }

    protected function manualActivateAccount(): void
    {
        $update = $this->db->prepare("UPDATE `users` SET `activated` = 1 WHERE `uid` = ? LIMIT 1;");
        $update->execute([$this->uid]);
    }
    protected function manualJoinGroup(): void
    {
        $group = $this->db->prepare("SELECT `group_id` FROM `users` WHERE `uid`= ?");
        $group->execute([$_SESSION['user']]);
        $group_row = $group->fetch();
        $update = $this->db->prepare("UPDATE `users` SET `group_id` = ? WHERE `uid` = ? LIMIT 1;");
        $update->execute([$group_row['group_id'], $this->uid]);
    }
    protected function manualInviteToGroup(): void
    {
        $select = $this->db->prepare("SELECT `uid` FROM `users` WHERE `email` = ? LIMIT 1;");
        $select->execute([$this->email]);
        $select_row = $select->fetch();

        $group = $this->db->prepare("SELECT `group_id` FROM `users` WHERE `uid`= ?");
        $group->execute([$_SESSION['user']]);
        $group_row = $group->fetch();

        $check =  $this->db->prepare("SELECT * FROM `group_invite` WHERE `group_id` = ? AND `invite_user-id` = ?");
        $check->execute([$group_row['group_id'], $select_row['uid']]);
        if ($check->rowCount() == 0) {
            $insert = $this->db->prepare("INSERT INTO `group_invite` (`group_id`, `invite_user-id`) VALUES (?,?)");
            $insert->execute([$group_row['group_id'], $select_row['uid']]);
        } else {
        }
    }

    protected function generateActivateKey(): bool
    {
        $sql = $this->db->prepare("SELECT `users`.`uid`,`key`,`last_send` FROM `users` INNER JOIN `activate_keys` ON `users`.`uid`=`activate_keys`.`uid` WHERE `users`.`email`= ? ");
        $sql->execute([$this->email]);
        if ($sql->rowCount() > 0) {
            $row = $sql->fetch();

            $current = date("Y-m-d H:i:s");
            $to_check = date("Y-m-d H:i:s", strtotime($row['last_send']) + 300);

            if ($to_check < $current) {
                $this->key = $row['key'];
                $update = $this->db->prepare("UPDATE `activate_keys` SET `last_send`= ? WHERE `uid`= ? ");
                $update->execute([Date("Y-m-d H:i:s"), $row['uid']]);
                return true;
            } else {
                return false;
            }
        } else {
            $this->key = substr(md5(rand()), 0, 24);
            $insert_key = $this->db->prepare("INSERT IGNORE INTO `activate_keys` (`key`, `uid`) VALUES (?, ?)");
            $insert_key->execute([$this->key, $this->uid]);
            return true;
        }
    }
    public function sendActivateLink(): array
    {

        $ans = $this->generateActivateKey();

        if ($ans == true) {
            $data['username'] = $this->username;
            $data['email'] = $this->email;
            $data['key'] = $this->key;
            $data['site'] = configAndConnect::URL;
            $this->sendEmail('register', $data);
            return array('type' => 'success', 'subject' => 'Sukces', 'value' => "Konto poprawnie utworzone, potwierdź swój email klikając w link w wiadomości którą do Ciebie wysłaliśmy. Możesz się już normalnie zalogować");
        } else {

            return array('type' => 'danger', 'subject' => 'Błąd', 'value' => "Wystąpił błąd z ponowną wysyłką wiadomości, limit wiadomości email to jeden na 5 minut :(");
        }
    }
    public function verifyAccount(string $key): bool
    {
        $select = $this->db->prepare("SELECT `uid` FROM `activate_keys` WHERE `key` = ? LIMIT 1;");
        $select->execute([$key]);
        if ($select->rowCount() > 0) { //Row found for key
            $result = $select->fetch();
            $update = $this->db->prepare("UPDATE `users` SET `activated` = 1 WHERE `uid` = ? LIMIT 1;");
            $update->execute([$result['uid']]);
            $delete = $this->db->prepare("DELETE FROM `activate_keys` WHERE `key` = ? LIMIT 1;");
            $delete->execute([$key]);
            return true; //Account activated
        } else {
            return false; //Key is invalid
        }
    }
}

class doResetAttempt extends configAndConnect
{
    public string $email;
    public string $key;
    protected PDO $db;

    public function __construct(string $email)
    {
        $this->email = $email;
        $this->db = (new configAndConnect)->db_connect('user');
    }

    protected function generateResetKey($uid): void
    {
        $this->key = substr(md5(rand()), 0, 24);
        $insert_key = $this->db->prepare("INSERT IGNORE INTO `reset_keys` (`key`, `uid`) VALUES (?, ?)");
        $insert_key->execute([$this->key, $uid]);
    }

    public function attemptReset(int $max_length = 60): array
    {
        if (filter_var($this->email, FILTER_VALIDATE_EMAIL) && strlen($this->email) <= $max_length) {
            $select = $this->db->prepare("SELECT `uid` FROM `users` WHERE `email` = ? LIMIT 1;");
            $select->execute([$this->email]);
            if ($select->rowCount() > 0) { //Row found for key
                $result = $select->fetch();
                $this->generateResetKey($result['uid']);
                $data['email'] = $this->email;
                $data['key'] = $this->key;
                $data['site'] = configAndConnect::URL;
                $this->sendEmail('resetpw', $data);
                return array('type' => 'success', 'subject' => 'Błąd', 'value' => "Wysłano email aktywujący czy coś");
            } else {
                return array('type' => 'danger', 'subject' => 'Błąd', 'value' => "Jeżeli dane są poprawne wysłaliśmy na podany adres wiadomość do resetowaniaa");
            }
        } else {
            return array('type' => 'danger', 'subject' => 'Błąd', 'value' => "Jeżeli dane są poprawne wysłaliśmy na podany adres wiadomość do resetowaniaa");
        }
    }




    // protected function sendVerifyEmail(): void
    // {
    //     require('PHPMailer/PHPMailer.php');
    //     require('PHPMailer/Exception.php');
    //     require('PHPMailer/SMTP.php');
    //     $mail = new PHPMailer\PHPMailer\PHPMailer();
    //     $mail->IsSMTP();
    //     $mail->CharSet = 'UTF-8';
    //     $mail->Host = configAndConnect::EMAIL_HOST;
    //     $mail->SMTPAuth = true;
    //     $mail->SMTPSecure = 'ssl';
    //     $mail->Port = configAndConnect::SMTP_PORT;
    //     $mail->SMTPOptions = array(
    //         'ssl' => array(
    //             'verify_peer' => false,
    //             'verify_peer_name' => false,
    //             'allow_self_signed' => true
    //         )
    //     );
    //     $mail->Username = configAndConnect::SMTP_USERNAME;
    //     $mail->Password = configAndConnect::SMTP_PASSWORD;
    //     $mail->setFrom(configAndConnect::EMAIL_ADDRESS, configAndConnect::WEBSITE_NAME);
    //     $mail->addAddress($this->email, 'New Account');
    //     $mail->isHTML(true);
    //     $mail->Subject = 'Account activation';
    //     $mail->Body = "<a href='" . configAndConnect::URL . "activate.php?key={$this->key}'>Click here to activate<a>";
    //     $mail->AltBody = "" . configAndConnect::URL . "activate.php?key={$this->key} URL to activate";
    //     $mail->send();
    // }

    // public function verifyAccount(string $key): bool
    // {
    //     $select = $this->db->prepare("SELECT `uid` FROM `activate_keys` WHERE `key` = ? LIMIT 1;");
    //     $select->execute([$key]);
    //     if ($select->rowCount() > 0) { //Row found for key
    //         $result = $select->fetch();
    //         $update = $this->db->prepare("UPDATE `users` SET `activated` = 1 WHERE `uid` = ? LIMIT 1;");
    //         $update->execute([$result['uid']]);
    //         $delete = $this->db->prepare("DELETE FROM `activate_keys` WHERE `key` = ? LIMIT 1;");
    //         $delete->execute([$key]);
    //         return true; //Account activated
    //     } else {
    //         return false; //Key is invalid
    //     }
    // }


    public function verifyAccount(string $key, string $password): bool
    {
        $select = $this->db->prepare("SELECT `uid` FROM `reset_keys` WHERE `key` = ? LIMIT 1;");
        $select->execute([$key]);
        if ($select->rowCount() > 0) { //Row found for key
            $result = $select->fetch();
            $update = $this->db->prepare("UPDATE `users` SET `password` = ? WHERE `uid` = ? LIMIT 1;");
            $update->execute([password_hash($password, PASSWORD_DEFAULT), $result['uid']]);
            $delete = $this->db->prepare("DELETE FROM `reset_keys` WHERE `key` = ? LIMIT 1;");
            $delete->execute([$key]);
            return true; //Account activated
        } else {
            return false; //Key is invalid
        }
    }
}

/*
 * Handle login attempts and authentication
 */

class doLoginAttempt extends configAndConnect
{
    private string $username;
    private string $stated_password;
    private string $real_password;
    private string $ip_address;
    public int $uid;
    protected PDO $db;

    public function __construct(string $username = "0", string $password = "0")
    {
        $this->username = $username;
        $this->stated_password = $password;
        $this->ip_address = $_SERVER['REMOTE_ADDR'];
        $this->db = (new configAndConnect)->db_connect('user');
    }

    protected function getUserDataForUsername(): bool
    {
        $select = $this->db->prepare("SELECT `uid`, `username`, `password` FROM `users` WHERE `username` = ? LIMIT 1;");
        $select->execute([$this->username]);
        if ($select->rowCount() > 0) { //Row found for username
            $result = $select->fetch();
            $this->uid = $result['uid'];
            $this->real_password = $result['password'];
            return true;
        } else { //Username not found
            return false;
        }
    }

    protected function checkPasswordCorrect(): bool
    {
        if (password_verify($this->stated_password, $this->real_password)) {
            return true; //Password is correct
        } else {
            return false; //Bad password
        }
    }

    protected function doLoginWasSuccess(): void
    {
        $update = $this->db->prepare("UPDATE `users` SET `login_count` = (login_count + 1), `last_login_at` = NOW(), `last_login_ip` = ? WHERE `uid` = ? LIMIT 1;");
        $update->execute([$this->ip_address, $this->uid]);
    }

    protected function addLoginFailCount(): void
    {
        $update = $this->db->prepare("UPDATE `users` SET login_fails = (login_fails + 1), `last_fail` = NOW() WHERE `username` = ? LIMIT 1;");
        $update->execute([$this->username]);
    }

    protected function addLoginFailAttempt(): void
    {
        $insert = $this->db->prepare('INSERT IGNORE INTO `login_attempts` (`username`, `ip`) VALUES (?, ?)');
        $insert->execute([$this->username, $this->ip_address]);
    }

    protected function getRecentFailCount(): int
    {
        $select = $this->db->prepare("SELECT COUNT(*) as the_count FROM `login_attempts` WHERE `ip` = ? AND `datetime` > (NOW() - INTERVAL 10 MINUTE);");
        $select->execute([$this->ip_address]);
        return $select->fetch()['the_count']; //login fails for IP in last 10 minutes
    }

    protected function isAccountLocked(): bool
    {
        $select = $this->db->prepare("SELECT `ip` FROM `account_locks` WHERE `ip` = ? LIMIT 1;");
        $select->execute([$this->ip_address]);
        $row = $select->fetch(PDO::FETCH_ASSOC);
        if (!empty($row)) { //Row found
            return true;
        } else { //NO row found
            return false;
        }
    }

    protected function hasLockTimePassed(): bool
    {
        $select = $this->db->prepare("SELECT `locked_until` FROM `account_locks` WHERE `ip` = ? LIMIT 1;");
        $select->execute([$this->ip_address]);
        $locked_until = $select->fetchColumn();
        $locked_until_formatted = DateTime::createFromFormat('Y-m-d H:i:s', $locked_until);
        if (new DateTime() > $locked_until_formatted) { //Time has passed
            $this->removeIpLock();
            return true;
        } else {
            return false;
        }
    }

    protected function removeIpLock(): void
    {
        $delete = $this->db->prepare("DELETE FROM `account_locks` WHERE `ip` = ? LIMIT 1;");
        $delete->execute([$this->ip_address]);
    }

    protected function addIpLock(): void
    {
        $time = new DateTime();
        $time->add(new DateInterval("PT" . configAndConnect::ACCOUNT_LOCK_MINUTES . "M"));
        $time_until = $time->format("Y-m-d H:i:s");
        $insert = $this->db->prepare('INSERT IGNORE INTO `account_locks` (`ip`, `locked_until`) VALUES (?, ?)');
        $insert->execute([$this->ip_address, $time_until]);
    }

    public function attemptLogin(string $redirect_to = '', bool $remember = false)
    {
        if ($this->getRecentFailCount() >= configAndConnect::FAIL_ATTEMPTS_ALLOWED) { //IP has had X or more fails in last 10 mins
            $this->addIpLock();
            return array('type' => 'danger', 'subject' => 'Błąd', 'value' => "Adres IP jest zablokowany na " . configAndConnect::ACCOUNT_LOCK_MINUTES . " minut");
        }
        if ($this->getUserDataForUsername()) { //Username found
            if ($this->isAccountLocked()) { //Account locked
                if ($this->hasLockTimePassed()) {
                    if ($this->checkPasswordCorrect()) { //Password is correct
                        $this->doLoginWasSuccess();

                        header("Location: $redirect_to");
                        exit;
                    } else { //Password is wrong
                        $this->addLoginFailCount(); //Add 1 onto login fail count
                        $this->addLoginFailAttempt(); //ip and datetime into login attempt fail logs
                        return array('type' => 'danger', 'subject' => 'Błąd', 'value' => "Błędne dane logowania, bądz konto zablokowana");
                    }
                } else {
                    return array('type' => 'danger', 'subject' => 'Błąd', 'value' => "Błędne dane logowania, bądz konto zablokowana"); //Still locked
                }
            } else { //Account not locked
                if ($this->checkPasswordCorrect()) { //Password is correct
                    $this->doLoginWasSuccess();
                    session_start();
                    $_SESSION['user'] = $this->uid; //Set session as uid
                    if ($remember == true) {
                        $hash = uniqid("s", true);
                        setcookie("remember", $hash, time() + 604800, "/");
                        $insert = $this->db->prepare('INSERT INTO `saved_device` (`user_id`, `cookie_value`,`cookie_expired`,`device_name`) VALUES (?,?,?,?)');
                        $ua = getBrowser();
                        $yourbrowser = $ua['name'] . " " . $ua['version'] . " , " . $ua['platform'];
                        $insert->execute([$this->uid, $hash, time() + 604800, $yourbrowser]);
                    }
                    header("Location: $redirect_to");
                    exit;
                } else { //Password is wrong
                    $this->addLoginFailCount(); //Add 1 onto login fail count
                    $this->addLoginFailAttempt(); //ip and datetime into login attempt fail logs
                    //return "Password is wrong for {$this->username}";//Dont use this, helps brute forcing.
                    return array('type' => 'danger', 'subject' => 'Błąd', 'value' => "Błędne dane logowania, bądz konto zablokowane"); //Be vague in error response
                }
            }
        } else {
            //return "Username: {$this->username} not found in DB";
            return array('type' => 'danger', 'subject' => 'Błąd', 'value' => "Błędne dane logowania, bądz konto zablokowane"); //Be vague in error response
        }
    }
    public function cookieExtension(string $cookie, $redirect_to = "")
    {

        $insert = $this->db->prepare('SELECT `user_id`,`cookie_expired` FROM `saved_device` WHERE `cookie_value` = ?');
        $insert->execute([$cookie]);
        if ($insert->rowCount() > 0) {
            $user = $insert->fetch();
            if ($user['cookie_expired'] > time()) {
                session_start();
                $_SESSION['user'] = $user['user_id'];
                $_SESSION['remember'] = '1';
                header("Location: $redirect_to");
            }
        }
    }
}

/*
 * Handles sessions: 'visitor is logged in', logout
 */

class sessionManage extends configAndConnect
{
    public int $uid;
    protected PDO $db;
    public function __construct()
    {
        $this->db = (new configAndConnect)->db_connect('user');
    }

    public function sessionStartIfNone()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start(); //No session stated... so start one
        }
    }

    public function checkIsLoggedIn(bool $redirect = true, string $redirect_to = "" . configAndConnect::URL . "login/"): bool
    {
        $this->sessionStartIfNone(); //Start session if none already started
        if (isset($_SESSION['user']) && !empty($_SESSION['user'])) {
            if (isset($_SESSION['remember'])) {
                $check = $this->db->prepare('SELECT `user_id` FROM `saved_device` WHERE `cookie_value` = ?');
                $check->execute([$_COOKIE['remember']]);
                if ($check->rowCount() > 0) {
                    return true;
                } else {
                    session_destroy();
                    return false;
                }
            } else {
                $this->uid = $_SESSION['user'];
                return true; //Logged in
            }
        } else {
            if ($redirect) { //Not logged in and do a redirect
                header("Location: $redirect_to");
                exit;
            }
            return false;
        }
    }

    public function redirectIfLoggedIn(string $redirect_to = "" . configAndConnect::URL . "account/")
    {
        $this->sessionStartIfNone(); //Start session if none already started
        if (isset($_SESSION['user']) && !empty($_SESSION['user'])) {
            $this->uid = $_SESSION['user'];
            header("Location: $redirect_to");
            exit;
        }
    }

    public function logout(bool $redirect = false, string $redirect_to = ''): bool
    {
        $this->sessionStartIfNone();
        if (isset($_SESSION['user']) && !empty($_SESSION['user'])) { //Logged in
            session_destroy();
            unset($_SESSION['user']);
            $db = (new configAndConnect)->db_connect('user');
            $update = $db->prepare("UPDATE `users` SET `logged_out` = NOW() WHERE `uid` = ? LIMIT 1;");
            $update->execute([$this->uid]);
            if ($redirect) { //Redirect after logout
                header("Location: $redirect_to");
                exit;
            } else {
                return true;
            }
        } else { //Was not logged in to begin with
            return false;
        }
    }


    public function isAccountActivated(): bool
    {
        $db = (new configAndConnect)->db_connect('user');
        $select = $db->prepare("SELECT `activated` FROM `users` WHERE `uid` = ? LIMIT 1;");
        $select->execute([$this->uid]);
        if ($select->fetch()['activated']) {
            return true; //Yes
        } else {
            return false; //No
        }
    }
}

/*
 * Details and data for logged in account
 */

class accountDetails extends configAndConnect
{
    protected PDO $db;
    public function accountData(): array
    {
        $db = (new configAndConnect)->db_connect('user');
        $select = $db->prepare("SELECT `uid`, `username`,`name`,`surname`, `created`, `login_count`, `login_fails`, `last_fail`, `email`,`privilege`,`google_api`,`google_calendar-default`,`activated`,`group_id` FROM `users` WHERE `uid` = ? LIMIT 1;");
        $select->execute([$_SESSION['user']]);

        return $select->fetchAll(PDO::FETCH_ASSOC)[0];
    }
    public function groupUsers()
    {
        $db = (new configAndConnect)->db_connect('user');
        $select = $db->prepare("SELECT `uid`,`username`,`name`,`surname` FROM `users` WHERE `group_id` = ( SELECT `group_id` FROM `users` WHERE `uid` = ? );");
        $select->execute([$_SESSION['user']]);
        $ans = array(
            $_SESSION['user'] => "username"
        );
        while ($row = $select->fetch()) {
            $ans[$row['uid']] = $row['name'] . " " . $row['surname'];
        }
        return $ans;
    }
    public function userGroupAllowed($id): bool
    {
        $users = $this->groupUsers();
        $i = 0;
        if (in_array($id, array_keys($users))) {
            $i = 1;
        }
        if ($i == 1) {
            return true;
        } else {
            return false;
        }
    }
    public function checkAllowed($table, $id)
    {
        $db = (new configAndConnect)->db_connect('data');
        $db2 = (new configAndConnect)->db_connect('user');

        $users = $this->groupUsers();
        $users_string = implode("','", array_keys($users));

        $select = $db->prepare("SELECT `user_id` FROM `{$table}` WHERE `{$table}_id` = ? AND `user_id` IN  ('{$users_string}')");
        $select->execute([$id]);
        if ($select->rowCount() > 0) {
            $row = $select->fetch();
            if ($row['user_id'] == $_SESSION['user']) {
                return 2;
            } else {
                return 1;
            }
        } else {

            return 0;
        }
    }
}

class configGoogle
{
    //Settings for email activation sender
    const CLIENT_ID = '351659155347-ka63561eo35fafrqlfo22f6gnc9g6qlp.apps.googleusercontent.com';
    const CLIENT_SECRET = 'SSwVHwDNNkGztgpZVxEPyzqP';
    const CLIENT_REDIRECT_URL = 'https://cmanager.pl/api/auth.php';
}
class GoogleCalendarApi extends configGoogle
{
    public function getGoogleDetails()
    {
        $arr = array();
        $arr['CLIENT_ID'] = configGoogle::CLIENT_ID;
        $arr['CLIENT_SECRET'] = configGoogle::CLIENT_SECRET;
        $arr['CLIENT_REDIRECT_URL'] = configGoogle::CLIENT_REDIRECT_URL;
        return $arr;
    }
    public function GetRefreshedAccessToken($refresh_token)
    {
        $url_token = 'https://accounts.google.com/o/oauth2/token';

        $curlPost = 'client_id=' . configGoogle::CLIENT_ID . '&client_secret=' . configGoogle::CLIENT_SECRET . '&refresh_token=' . $refresh_token . '&grant_type=refresh_token';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url_token);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
        $data = json_decode(curl_exec($ch), true);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($http_code != 200)
            print_r('Error : Failed to refresh access token');

        return $data;
    }

    public function GetAccessToken($code)
    {
        $url = 'https://accounts.google.com/o/oauth2/token';

        $curlPost = 'client_id=' . configGoogle::CLIENT_ID  . '&redirect_uri=' . configGoogle::CLIENT_REDIRECT_URL . '&client_secret=' . configGoogle::CLIENT_SECRET . '&code=' . $code . '&grant_type=authorization_code';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
        $data = json_decode(curl_exec($ch), true);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($http_code != 200)
            print_r('Error : Failed to receive access token');

        return $data;
    }

    public function GetUserCalendarTimezone($access_token)
    {
        $url_settings = 'https://www.googleapis.com/calendar/v3/users/me/settings/timezone';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url_settings);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $access_token));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $data = json_decode(curl_exec($ch), true);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($http_code != 200)
            print_r('Error : Failed to get calendar timezone');
        return $data['value'];
    }
    public function GetCalendarsList($access_token)
    {
        $url_parameters = array();

        $url_parameters['fields'] = 'items(id,summary,timeZone)';
        $url_parameters['minAccessRole'] = 'owner';

        $url_calendars = 'https://www.googleapis.com/calendar/v3/users/me/calendarList?' . http_build_query($url_parameters);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url_calendars);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $access_token));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $data = json_decode(curl_exec($ch), true);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($http_code != 200)
            print_r('Error : Failed to get calendars list');

        return $data['items'];
    }
    public function GetEventList($calendar_id, $access_token)
    {
        $url_calendars = 'https://www.googleapis.com/calendar/v3/calendars/' . $calendar_id . "/events";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url_calendars);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $access_token));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $data = json_decode(curl_exec($ch), true);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($http_code != 200)
            print_r('Error : Failed to get calendars list');

        return $data;
    }
    public function checkSynced($calendar_id, $event_id, $access_token)
    {

        if ($event_id != null) {


            $url_calendars = 'https://www.googleapis.com/calendar/v3/calendars/' . $calendar_id . "/events/" . $event_id . "?status=confirmed";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url_calendars);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $access_token));
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            $data = json_decode(curl_exec($ch), true);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($http_code != 200 || $data['status'] == 'cancelled')
                return 0;

            return 1;
        } else {
            return 0;
        }
    }

    public function CreateCalendarEvent($calendarId, $event, $access_token)
    {
        $url_events = 'https://www.googleapis.com/calendar/v3/calendars/' . $calendarId . '/events';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url_events);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $access_token, 'Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($event));
        $data = json_decode(curl_exec($ch), true);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($http_code != 200)
            print_r('Error : Failed to create event');

        return $data['id'];
    }
    public function RemoveCalendarEvent($calendarId, $event, $access_token)
    {
        $url_events = 'https://www.googleapis.com/calendar/v3/calendars/' . $calendarId . '/events/' . $event;
        echo $url_events;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url_events);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $access_token, 'Content-Type: application/json'));
        $data = json_decode(curl_exec($ch), true);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($http_code != 200)
            print_r('Error : Failed to delete event');

        return $data;
    }
}
class Detect
{
    public static function systemInfo()
    {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $os_platform    = "Unknown OS Platform";
        $os_array       = array(
            '/windows phone 8/i'    =>  'Windows Phone 8',
            '/windows phone os 7/i' =>  'Windows Phone 7',
            '/windows nt 6.3/i'     =>  'Windows 8.1',
            '/windows nt 6.2/i'     =>  'Windows 8',
            '/windows nt 6.1/i'     =>  'Windows 7',
            '/windows nt 6.0/i'     =>  'Windows Vista',
            '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
            '/windows nt 5.1/i'     =>  'Windows XP',
            '/windows xp/i'         =>  'Windows XP',
            '/windows nt 5.0/i'     =>  'Windows 2000',
            '/windows me/i'         =>  'Windows ME',
            '/win98/i'              =>  'Windows 98',
            '/win95/i'              =>  'Windows 95',
            '/win16/i'              =>  'Windows 3.11',
            '/macintosh|mac os x/i' =>  'Mac OS X',
            '/mac_powerpc/i'        =>  'Mac OS 9',
            '/linux/i'              =>  'Linux',
            '/ubuntu/i'             =>  'Ubuntu',
            '/iphone/i'             =>  'iPhone',
            '/ipod/i'               =>  'iPod',
            '/ipad/i'               =>  'iPad',
            '/android/i'            =>  'Android',
            '/blackberry/i'         =>  'BlackBerry',
            '/webos/i'              =>  'Mobile'
        );
        $found = false;
        $device = '';
        foreach ($os_array as $regex => $value) {
            if ($found)
                break;
            else if (preg_match($regex, $user_agent)) {
                $os_platform    =   $value;
                $device = !preg_match('/(windows|mac|linux|ubuntu)/i', $os_platform)
                    ? 'MOBILE' : (preg_match('/phone/i', $os_platform) ? 'MOBILE' : 'SYSTEM');
            }
        }
        $device = !$device ? 'SYSTEM' : $device;
        return array('os' => $os_platform, 'device' => $device);
    }

    public static function browser()
    {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];

        $browser        =   "Unknown Browser";

        $browser_array  = array(
            '/msie/i'       =>  'Internet Explorer',
            '/firefox/i'    =>  'Firefox',
            '/safari/i'     =>  'Safari',
            '/chrome/i'     =>  'Chrome',
            '/opera/i'      =>  'Opera',
            '/netscape/i'   =>  'Netscape',
            '/maxthon/i'    =>  'Maxthon',
            '/konqueror/i'  =>  'Konqueror',
            '/mobile/i'     =>  'Handheld Browser'
        );

        foreach ($browser_array as $regex => $value) {
            if ($found)
                break;
            else if (preg_match($regex, $user_agent, $result)) {
                $browser    =   $value;
            }
        }
        return $browser;
    }
}