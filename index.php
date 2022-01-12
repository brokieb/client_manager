<?php
$ver = "3.00";
require_once('class.php');
define('directly', true);
$connect = new ConfigAndConnect;
$conn_user = $connect->db_connect('user');
$conn_form = $connect->db_connect('form');
$conn_data = $connect->db_connect('data');
$session = new sessionManage();
$logged_in = $session->checkIsLoggedIn(false);
if ($logged_in) {
    if ($_SESSION['user'] == 1) {
    }
}
?>
<!DOCTYPE html>
<?php
$przerwa = 0;
if ($przerwa == 1) {
?>
    Przerwa techniczna, proszę o chwilę cierpliwości
<?php
} else {


?>
    <html lang="pl">

    <head>
        <meta HTTP-EQUIV="Content-Type" content="text/html; charset=utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="theme-color" content="#FFD700">
        <?php
        if ($logged_in) {
            $select = $conn_form->prepare("SELECT `nav_title` FROM `nav` WHERE `nav_privilege`=1 AND `nav_href`=? ");
            $session = $_SESSION['user'];
        } else {
            $select = $conn_form->prepare("SELECT `nav_title` FROM `nav` WHERE `nav_privilege`=0 AND `nav_href`=? ");
            $session = null;
        }



        if (isset($_GET['site'])) {
            $select->execute([$_GET['site']]);
            // $row_check = mysqli_fetch_array($query_check, MYSQLI_ASSOC);
            if ($select->rowCount() > 0) {
                $row_check = $select->fetch();
        ?>
                <title>C Manager - <?= $row_check['nav_title'] ?></title>
            <?php
            } else {
            ?>
                <title>C Manager</title>
            <?php
            }
        } else {
            ?>
            <title>C Manager - Ładowanie..</title>
        <?php
        }
        ?>
        <link rel="icon" type="image/png" sizes="32x32" href="img/favicon-32x32.png">
        <link rel="manifest" href="manifest.json">
        <?PHP
        // echo $sql_theme = "select * from `preference_user` where `preference_id`=2 AND `user_id`=" . $_SESSION['user'] . " ";
        //                             $query_theme = mysqli_query($conn, $sql_theme);
        //                             $row_theme = mysqli_fetch_array($query_theme, MYSQLI_ASSOC);
        //                             if (empty($row_theme)) {
        //                                 $theme = "dark";
        //                             } else {
        //                                 $theme = $row_theme['preference_value'];
        //                             }jquery-ui.css
        ?>

        <link href="style/lib/bootstrap-dark.css?ver=<?= $ver ?>" rel="stylesheet">
        <link href="style/lib/jquery-ui.css?ver=<?= $ver ?>" rel="stylesheet">
        <link href="style/lib/intlTelInput.css?ver=<?= $ver ?>" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href='style/main.css?ver=<?= $ver ?>'>
        <link rel="stylesheet" type="text/css" href='style/all.min.css?ver=<?= $ver ?>'>
        <link rel="stylesheet" type="text/css" href='style/lib/bootstrap-select.min.css?ver=<?= $ver ?>'>
        <link rel="stylesheet" href="style/lib/select2-bootstrap-5-theme.min.css?ver=<?= $ver ?>" />
        <link rel="stylesheet" href="style/lib/select2.min.css?ver=<?= $ver ?>" />

        <script src="js/lib/jquery-3.5.1.min.js?ver=<?= $ver ?>"></script>
        <script src="js/lib/jquery.dataTables.min.js?ver=<?= $ver ?>"></script>
        <script src="js/lib/bootstrap.bundle.min.js?ver=<?= $ver ?>'" crossorigin="anonymous">
        </script>
        <script src="js/lib/maska-master.js?ver=<?= $ver ?>"></script>
        <script src="js/lib/jquery-ui.js?ver=<?= $ver ?>"></script>
        <script src="js/lib/jquery.ui.touch-punch.min.js?ver=<?= $ver ?>"></script>
        <script src="js/lib/jquery.cookie.js?ver=<?= $ver ?>"></script>
        <script src="js/lib/printThis.js?ver=<?= $ver ?>"></script>
        <script src="js/lib/clipboard.min.js?ver=<?= $ver ?>"></script>
        <script src="js/lib/select2.min.js?ver=<?= $ver ?>"></script>
        <!-- Latest compiled and minified CSS -->

        <!-- Latest compiled and minified JavaScript -->


    </head>

    <body id="body-pd" class='bg-dark' data-site-href='<?= $_GET['site'] ?>' data-s="<?= $session ?>">
        <?php
        ?>
        <div class='modals-container'>
            <?php
            include('modal.php');
            ?>
        </div>
        <?php
        if (isset($_POST['000mode'])) {
        ?>
            <div class="d-flex align-items-center">
                <div class="spinner-grow" style="width: 3rem; height: 3rem;" role="status">
                    <span class="visually-hidden">Ładowanie...</span>
                </div>
                <span class='ps-3'>
                    Przetwarzanie zapytania..
                </span>
            </div>
            <?php
            try {
                if ($logged_in) {
                    $det = new accountDetails();
                    $details = $det->accountData();
                }
                include('forms-scripts.php');
            } catch (Exception $e) {
                $this_id = uniqid();
                $err = array(
                    "id" => $this_id,
                    "code" => $e,
                    "POST" => $_POST
                );
                $insert_log = $conn_data->prepare("INSERT INTO errors (`err_id`,`err_json`) values ( ? , ?)");
                if ($insert_log->execute([$this_id, json_encode($err)])) {

                    $ans = array(
                        'add' => time(),
                        'uid' => $uid,
                        'type' => 'danger',
                        'subject' => 'Błąd!!',
                        'value' => "Nie udało się bezbłędnie wykonać twojego zapytania, możliwe że nie zobaczysz zmian/nowych pozycji. Całe twoje zapytanie zostało zabezpieczone, proszę skontaktować się z administratorem i podać kod błędu : <" . $this_id . "> "
                    );

                    $_SESSION['alerts'][$uid] = $ans;

                    if (isset($_GET['site'])) {
            ?>
                        <meta http-equiv="refresh" content="0;url=<?= configAndConnect::URL ?>index.php?site=<?= $_GET['site'] ?>" />
                    <?php
                    } else {
                    ?>
                        <meta http-equiv="refresh" content="0;url=<?= configAndConnect::URL ?>index.php" />
                    <?php
                    }


                    ?>

            <?php

                }
            }
        } else {
            include('nav.php');
            ?>
            <!--Container Main start-->
            <div class='container-xxl container-fluid' style='min-height:90vh;'>
                <div class='row'>
                    <?php
                    if ($allow == 1) {
                        if ($logged_in) {
                            if (isset($_GET['content'])) {
                                $content = explode(".", $_GET['content']);
                                $site_content = explode("-", $_GET['site']);
                                switch ($content[0]) {
                                    case "tel":
                                        $select = $conn_data->prepare("SELECT `build_id` as `id`,'build' as `table` FROM `build` WHERE replace(`build_client-phone`,' ','') LIKE :content UNION SELECT `client_id` as 'id','client' as `table` FROM `client` WHERE replace(`client_client-phone`,' ','') LIKE :content LIMIT 1");
                                        $value = $content[1];
                                        $comment = "Szczegóły pozycji o ID #";
                                        break;
                                    default:
                                        switch ($site_content[0]) {
                                            case 'build':
                                                $comment = "Szczegóły nieruchomości o ID #";
                                                $select = $conn_data->prepare("SELECT `build_id` as 'id',`build_client-phone` as 'phone' FROM `build` WHERE `user_id`={$_SESSION['user']} AND `build_id`=:content; ");
                                                break;
                                            case 'client':
                                                $comment = "Szczegóły klienta o ID #";
                                                $select = $conn_data->prepare("SELECT `client_id` as 'id',`client_client-phone` as 'phone' FROM `client` WHERE `user_id`={$_SESSION['user']} AND `client_id`=:content; ");
                                                break;
                                            case 'meet':
                                                $comment = "Szczegóły spotkania o ID #";
                                                $select = $conn_data->prepare("SELECT `meet_id` as 'id',`meet_client-phone` as 'phone' FROM `meet` WHERE `user_id`={$_SESSION['user']} AND `meet_id`=:content; ");
                                                break;
                                            default:
                                                $select = null;
                                                break;
                                        }
                                        $value = $_GET['content'];
                                        break;
                                }

                                if ($select != null) {

                                    $select->bindValue(":content", "%" . $value);
                                    $select->execute();
                                    if ($select->rowCount() > 0) {

                                        while ($row = $select->fetch()) {
                    ?>
                                            <button class='btn btn-lg btn-primary' data-content-get='<?= $value ?>' data-toggle="modal" data-title="<?= $comment ?><?= $row['id'] ?>" data-content="add-<?= $site_content[0] ?>" data-modal="2" data-call="<?= $row['phone'] ?>" data-id="<?= $row['id'] ?>">Otwórz szczegóły z linku</button>

                                        <?php
                                        }
                                    } else {
                                        ?>
                                        <div class="alert alert-warning" role="alert">
                                            Nie udało się znaleźć odpowiednich szczegółów dla dzwoniącego
                                            <?php
                                            ?>
                                        </div>
                                <?php
                                    }
                                }
                            }
                        }
                        if ($logged_in) {
                            if ($details['activated'] == 0) {
                                ?>
                                <div class="alert alert-danger" role="alert">
                                    Konto nie zostało jeszcze aktywowane! Proszę o kliknięcie w link aktywujący przesłany do Ciebie w
                                    wiadomości
                                    Email na adres podany przy rejestracji.</br>
                                    Jeżeli wiadomość jeszcze do Ciebie nie dotarła <a href='#' class="resend-activate-link">KLIKNIJ TUTAJ</a>

                                </div>
                            <?php
                            }
                            if ($details['name'] == null || $details['surname'] == null) {
                            ?>
                                <div class="alert alert-danger" role="alert">
                                    Do poprawnego identyfikowania agentów w grupach wymagamy uzupełnienia dodatkowych informacji o sobie,
                                    dane które są wymagane to:
                                    <ul>
                                        <li>Imię</li>
                                        <li>Nazwisko</li>
                                    </ul>
                                    Dane te, proszę przekazać w wiadomości na chacie w prawym dolnym rogu - dane te zostaną uzupełnione
                                    przez administratora
                                    Uwaga! nieuzupełnienie tych danych będzie uniemożliwiało korzystanie z aplikacji!
                                    <a href='#' class="resend-activate-link">KLIKNIJ TUTAJ</a>

                                </div>
                        <?php
                            }
                        }
                        ?>





                        <div class="shadow-lg fixed-bottom p-1 m-0 bg-dark custom-alert d-flex" style="z-index:100" data-alert='1022'>
                            <h6 class='m-1 text-primary'>Dodaj naszą aplikację do ekranu głównego!<button class='btn btn-sm btn-outline' data-hide-alert='true'>Ukryj komunikat</button></h6>
                            <button id='get-app' class="add-button btn btn-sm btn-success"><i class="fas fa-download pe-2"></i>Zainstaluj
                                aplikację</button>
                        </div>

                        <?php
                        if (isset($catalog)) {
                            if ($details['privilege'] != '0') {
                                include("sites/{$catalog}/{$_GET['site']}.php");
                            } else {
                                include("sites/no-payment.php");
                            }
                        } else {
                            include("sites/{$_GET['site']}.php");
                        }
                        ?>

                        <?Php
                    } else {
                        if (!isset($_GET['site'])) {
                        ?>
                            <div class='d-flex justify-content-center pt-5'>
                                <div div class="spinner-border" role="status">
                                    <span class="visually-hidden">Ładowanie...</span>
                                </div>

                            </div>

                            <?php
                        } else {
                            if (isset($first['nav_href'])) {
                            ?>
                                <div class='d-flex justify-content-center pt-5'>
                                    <div class="spinner-border" role="status">
                                        <span class="visually-hidden">Ładowanie...</span>
                                    </div>
                                    <span class='px-2'>
                                        Zaczekaj chwilę, system analizuję poprawność twoich danych...
                                    </span>
                                </div>
                                <script>
                            window.location.href = 'index.php?site=<?= $first['nav_href'] ?>';
                        </script>
                            <?php
                            } else {
                            ?>
                                Skontaktuj się z administratorem :(
                    <?php
                            }
                        }
                    }
                    ?>
                </div>
            </div>
            <!--Container Main end-->


            <div class='position-fixed col-xl-3 col-sm-6 alerts' style='bottom:120px;right:10px'>
                <?php
                if (isset($_SESSION['alerts'])) {
                    foreach ($_SESSION['alerts'] as $z) {
                        if (time() - $z['add'] < 30) {
                ?>
                            <div class="alert alert-<?= $z['type'] ?> alert-dismissible fade show" role="alert" id='myAlert'>
                                <strong><?= $z['subject'] ?></strong> <?= $z['value'] ?>
                                <button type="button" class="btn-close close-alert" data-bs-dismiss="alert" aria-label="Close" data-id='<?= $z['uid'] ?>'></button>
                            </div>

                <?php
                        } else {
                            unset($_SESSION['alerts'][$z['uid']]);
                        }
                    }
                }
                ?>
            </div>
        <?php
        }
        if ($logged_in) {
        ?>

            <script>
                // Here is an example showing how you could do it using PHP
                $("a[data-chat]").click(function() {
                    console.log("chat!!")
                    var Tawk_API = Tawk_API || {};
                    Tawk_API.visitor = {
                        name: '<?= $details['username'] ?>',
                        email: '<?= $details['email'] ?>',
                        hash: '<?= hash_hmac("sha256", $details['email'], "83571b51844615aa2c239b0849297682398a4653") ?>'
                    };
                    Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
                    (function() {
                        var s1 = document.createElement("script"),
                            s0 = document.getElementsByTagName("script")[0];
                        s1.async = true;
                        s1.src = 'https://embed.tawk.to/60cc39fe65b7290ac63695bc/1f8es52fr';
                        s1.charset = 'UTF-8';
                        s1.setAttribute('crossorigin', '*');
                        s0.parentNode.insertBefore(s1, s0);
                    })();
                    // Tawk_API.showWidget();
                })
            </script>
        <?php
        }
        ?>
        <script src="js/lib/intlTelInput.js?ver=<?= $ver ?>"></script>
        <script src='js/nav.js?ver=<?= $ver ?>'></script>
        <script src='js/main.js?ver=<?= $ver ?>'></script>
        <script src='js/ajax.js?ver=<?= $ver ?>'></script>

        <!-- (Optional) Latest compiled and minified JavaScript translation files -->

        <footer class='position-aboslute end-0 pt-5 mt-5'>
            Client Manager 2021 | <?= $ver ?> | <a href='?site=changelog'>Historia zmian</a> | <a href='?site=privacy'>Polityka prywatności</a>
        </footer>
    </body>

    </html>
<?php
}
?>