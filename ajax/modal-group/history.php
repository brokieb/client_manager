<?php
include("../default.php");
$content = explode("-", $_POST['contentName'])[1];
switch ($content) {
    case 'build':
        $content_reverse = "client";
        $modal_title = "Szczegóły klienta o ID ";
        break;
    case 'client':
        $content_reverse = "build";
        $modal_title = "Szczegóły nieruchomości o ID ";
        break;
}
$det = new accountDetails;
if ($det->checkAllowed($content, $_POST['contentId'])) {

?>
<h3>Pozycje oznaczone</h3>
<div class="p-2">
        <h5>Legenda </h5>  
        <span class="badge p-2 m-1 bg-">Wstępnie zaakceptowani klienci</span>
        <span class="badge p-2 m-1 bg-warning">Odrzuceni klienci</span>
            </div>
<table class='table table-bordered'>
    <thead>
        <tr>
            <td>ID</td>
            <td>Imię i nazwisko</td>
            <td>Notatka</td>
            <td>Przyciski</td>

        </tr>
    </thead>
    <tbody>
        <?php
            $select_client = $conn_data->prepare("SELECT `match_direction` as 'direction',`{$content_reverse}`.`user_id`,`{$content_reverse}_client-name` as 'name',`{$content_reverse}_client-surname` as 'surname',`{$content_reverse}_comment` as 'comment',`{$content_reverse}_client-phone` as 'phone',`{$content_reverse}`.`{$content_reverse}_id` as 'id' FROM {$content_reverse} INNER JOIN `matches` ON `{$content_reverse}`.`{$content_reverse}_id` = `matches`.`{$content_reverse}_id` WHERE `matches`.`{$content}_id`= ? AND `matches`.`user_id` = {$_SESSION['user']} ORDER BY `match_direction` DESC");
            $select_client->execute([$_POST['contentId']]);
            if ($select_client->rowCount() > 0) {
                while ($row_client = $select_client->fetch(PDO::FETCH_ASSOC)) {
                    switch ($row_client['direction']) {
case '1':
?>
 <tr>
<?php
    break;
case '0':
    ?>
    <tr class='table-warning'>
        <?php
     break;

                    }
?>
       
            <td><?=$row_client['direction']?>  <?= $row_client['id'] ?></td>
            <td>
                <?= $row_client['name'] ?> <?= $row_client['surname'] ?>
                <?php
                                    if ($row_client['user_id'] != $_SESSION['user']) {
                                    ?>
                <p class='text-danger text-bold m-0'>
                    <?php
                                            $this_details = $conn_user->prepare("SELECT `email` FROM `users` WHERE `uid` = ?");
                                            $this_details->execute([$row_client['user_id']]);
                                            $details_row = $this_details->fetch();
                                            ?>
                    KLIENT <?= $details_row['email'] ?>
                </p>
            </td>
            <?php
                                    } else {
?>
            <td><?= $row_client['name'] ?> <?= $row_client['surname'] ?></td>
            <?php
                                    }
                                ?>
            <td><i><?= $row_client['comment'] ?></i></td>
            <?php
                    switch ($row_client['direction']) {
                        case '1': //pozycja zaakceptowana
            ?>

            <td data-tbl-title="buttons">
                <div class='d-flex flex-wrap justify-content-around'>
                    <button type="button"
                            class="btn btn-sm btn-primary m-1"
                            data-toggle='modal'
                            data-content="add-<?= $content_reverse ?>"
                            data-modal='2'
                            data-id="<?= $value ?>"
                            data-title="<?= $modal_title ?> #<?= $value ?>"
                            data-call="<?= $row_client['phone'] ?>"
                            data-bs-tooltip="tooltip"
                            data-bs-placement="top"
                            title="Informacje">
                        <i class="fas fa-info-circle"></i><span>Informacje</span>
                    </button>
                    <?php
                                        ?>
                    <a href="tel:<?= $row['phone'] ?>"
                       class="btn btn-sm btn-primary m-1"
                       data-bs-tooltip="tooltip"
                       title="Zadzwoń do klienta"><i class="fas fa-phone"></i><span>Zadzwoń</span>
                    </a>
                    <!-- <button type='button' class="btn btn-sm btn-primary m-1" data-bs-tooltip="tooltip" title="Utwórz spotkanie"><i class=" fas fa-calendar-plus"></i><span>Utwórz spotkanie</span>
                                </button> -->
                </div>
            </td>
            <?php
                            break;
                        case '0': //pozycja anulowana
                        ?>
            <td data-tbl-title="buttons">
                <div class='d-flex flex-wrap justify-content-around'>
                    <button type="button"
                            class="btn btn-sm btn-primary m-1"
                            data-toggle='modal'
                            data-content="add-<?= $content_reverse ?>"
                            data-modal='2'
                            data-id="<?= $value ?>"
                            data-title="Szczegóły klienta o ID #<?= $value ?>"
                            data-call="<?= $row_client['phone'] ?>"
                            data-bs-tooltip="tooltip"
                            data-bs-placement="top"
                            title="Informacje">
                        <i class="fas fa-info-circle"></i><span>Informacje</span>
                    </button>
                    <?php
                                        if ($row_client['user_id'] == $_SESSION['user']) {
                                        ?>
                    <a href="tel:<?= $row['phone'] ?>"
                       class="btn btn-sm btn-primary m-1"
                       data-bs-tooltip="tooltip"
                       title="Zadzwoń do klienta"><i class="fas fa-phone"></i><span>Zadzwoń</span>
                    </a>
                    <?php
                                        }
                                        ?>
                </div>
            </td>
            <?php
                            break;
                            
                    }
                    ?>
        </tr>
        <?php
                }
            } else {
                ?>
        ?>
        <div class="alert alert-info"
             role="alert">
            Żadna propozycja nie została wstępnie oznaczona
        </div>
        <?php
            }
            ?>
    </tbody>
</table>
<h3>Historia spotkań</h3>
<?php
    $select = $conn_data->prepare("SELECT `user_id`,`meet_id`,`meet_client-name`,`meet_build-address`,`meet_status`,`meet_client-phone`,`meet_build-id`,`meet_client-id` FROM `meet` WHERE `meet_{$content}-id`= ? ");

    $select->execute([$_POST['contentId']]);
    if ($select->rowCount() > 0) {
    ?>
<table class='table table-bordered'>
    <thead>
        <tr>
            <td>ID</td>
            <td>Adres</td>
            <td>Imię i nazwisko</td>
            <td>Status</td>
            <td>Przyciski</td>
        </tr>
    </thead>
    <tbody>
        <?php
                while ($row = $select->fetch()) {
                ?>
        <tr class='t'>
            <td><?= $row['meet_id'] ?></td>
            <td>
                <?php
                            if ($row['meet_build-id'] == null) {
                            ?>
                <?= $row['meet_build-address'] ?>
                <?php
                            } else {
                                $sql = $conn_data->prepare("SELECT CONCAT_WS('',upper(left(`build_client-name`,1)),'. ',`build_client-surname`,', ',`build_road`,' ',`build_house-number`, CASE WHEN `build_local-number` IS NULL THEN ' ' ELSE CONCAT('/',`build_local-number`) END) as title FROM `build` WHERE `user_id`={$_SESSION['user']}  AND `build_id`= ?  ");
                                $sql->execute([$row['meet_build-id']]);
                                $row_details =  $sql->fetch();
                                echo $row_details['title'];
                            }
                            ?>
            </td>
            <td>
                <?php
                            if ($row['meet_client-id'] == null) {
                            ?>
                <?= $row['meet_client-name'] ?>
                <?php
                            } else {
                                $sql = $conn_data->prepare("SELECT CONCAT(`client_client-name`,' ',`client_client-surname`) as title FROM `client` WHERE `user_id`={$_SESSION['user']} AND `client_id`= ? ");
                                $sql->execute([$row['meet_client-id']]);
                                $row_details =  $sql->fetch();
                            ?>
                <?= $row_details['title'] ?>
                <?php
                            }
                            ?></td>
            <td>
                <?php
                            if ($row['meet_status'] == null) {
                            ?>
                Nie przypisano statusu
                <?php
                            } else {
                            ?>
                <?= $row['meet_status'] ?>
                <?php
                            }
                            ?>
            </td>
            <td>
                <button type="button"
                        class="btn btn-sm btn-primary mx-2"
                        data-toggle='modal'
                        data-content="add-meet"
                        data-modal="2"
                        data-title="Szczegóły spotkania o ID #<?= $row['meet_id'] ?>"
                        data-call="<?= $row['meet_client-phone'] ?>"
                        data-id="<?= $row['meet_id'] ?>"
                        data-bs-tooltip="tooltip"
                        data-bs-placement="top"
                        title="Informacje">
                    <i class="fas fa-info-circle"></i><span>Informacje</span>
                </button>
            </td>
        </tr>
        <?php
                }
                ?>
    </tbody>
</table>
<?php
    } else {
    ?>
<div class="alert alert-info"
     role="alert">
    Nie udało się znależć żadnego spotkania z udziałem tej pozycji
</div>
<hr>
<?php
    }
} else {
    include(__DIR__ . '/../../view/show-deny.php');
}

?>