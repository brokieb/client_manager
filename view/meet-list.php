<?php
switch ($row['order_table']) {
    case '1':
?>
<tr data-id='<?= $row['meet_id'] ?>'
    class='table-success fw-bolder'>
    <?php
        break;
    case '2':
        ?>

<tr data-id='<?= $row['meet_id'] ?>'>
    <?php
        break;
    case '3':
        ?>
<tr data-id='<?= $row['meet_id'] ?>'
    class='table-danger'>
    <?php
        break;
}
    ?>
    <td colspan='6'
        class='p-0'>
        <table class='w-100'>
            <tr class="d-flex flex-wrap align-items-center">

                <?php
                if ($row['meet_client-id'] != null) {
                    if ($details['group_id'] != null) {
                        $ans_group = array();
                        foreach ($group_det as $key => $value) {
                            $ans_group[] = $key;
                        }
                        $group_users = implode("','", $ans_group);
                    } else {
                        $group_users = $_SESSION['user'];
                    }
                    $select_client = $conn_data->prepare("SELECT `client_client-name`,`client_client-surname`,`client_client-phone`,`user_id` FROM `client` WHERE `user_id` IN ('{$group_users}') AND `client_id`=?;  ");
                    $select_client->execute([$row['meet_client-id']]);

                    if ($select_client->rowCount() > 0) {
                        $client = $select_client->fetch();
                ?>
                <td class='p-2 col-12 col-lg-<?= $config['columns']['Imię i nazwisko'] ?>'
                    data-tbl-title="name">
                    <?php
                            if ($_SESSION['user'] != $row['user_id']) {
                            ?>
                    <i class="fas fa-user-tie"></i>
                    <?php
                            }
                            ?>
                    <a href='#'
                       data-toggle="modal"
                       data-content="add-client"
                       data-modal="2"
                       data-call="<?= $client['client_client-phone'] ?>"
                       data-title="Szczegóły klienta o ID #<?= $row['meet_client-id'] ?>"
                       data-id="<?= $row['meet_client-id'] ?>">
                        #<?= $row['meet_id'] ?>
                        <?= $client['client_client-name'] ?> <?= $client['client_client-surname'] ?><i
                           class="ps-1 fas fa-external-link-alt"></i></a>
                    <?php
                        $phone = $client['client_client-phone'];
                    } else {
                        $phone = "Błąd uprawnień";
                        ?>
                <td class='p-2 col-12 col-lg-<?= $config['columns']['Imię i nazwisko'] ?>'
                    data-tbl-title="name">#<?= $row['meet_id'] ?> Błąd uprawnień</td>
                <?php
                    }
                } elseif ($row['meet_client-group-id'] != null) {

                    if ($details['group_id'] != null) {
                        $ans_group = array();
                        foreach ($group_det as $key => $value) {
                            $ans_group[] = $key;
                        }
                        $group_users = implode("','", $ans_group);
                        $select_client = $conn_data->prepare("SELECT `client_client-name`,`client_client-surname`,`client_client-phone`,`user_id` FROM `client` WHERE `user_id` IN ('{$group_users}') AND `client_id`=?;  ");
                        $select_client->execute([$row['meet_client-group-id']]);
                        $client = $select_client->fetch();
                    ?>

                <?php
                        if ($client['user_id'] != $_SESSION['user']) {
                        ?>
                <td class='p-2 col-12 col-lg-<?= $config['columns']['Imię i nazwisko'] ?>'
                    data-tbl-title="name"><?= $client['user_id'] ?><a href='#'
                       data-toggle="modal"
                       data-content="add-client"
                       data-modal="2"
                       data-title="Szczegóły klienta o ID #<?= $row['meet_client-group-id'] ?>"
                       data-id="<?= $row['meet_client-group-id'] ?>">#<?= $row['meet_id'] ?>
                        <?= $client['client_client-name'] ?> <?= $client['client_client-surname'] ?><i
                           class="ps-1 fas fa-external-link-alt"></i></a>
                    <p class='text-link'>Agent <?= $group_det[$client['user_id']] ?></p>
                    <?php
                            $phone = "* TEL UKRYTY *";
                        } else {
                            ?>
                <td class='p-2 col-12 col-lg-<?= $config['columns']['Imię i nazwisko'] ?>'
                    data-tbl-title="name"><a href='#'
                       data-toggle="modal"
                       data-content="add-client"
                       data-modal="2"
                       data-call="<?= $client['client_client-phone'] ?>"
                       data-title="Szczegóły klienta o ID #<?= $row['meet_client-group-id'] ?>"
                       data-id="<?= $row['meet_client-group-id'] ?>">
                        <?php
                                    if ($_SESSION['user'] != $row['user_id']) {
                                    ?>
                        <i class="fas fa-user-tie"></i>
                        <?php
                                    }
                                    ?>

                        #<?= $row['meet_id'] ?>
                        <?= $client['client_client-name'] ?> <?= $client['client_client-surname'] ?><i
                           class="ps-1 fas fa-external-link-alt"></i></a>
                    <?php
                            $phone = $client['client_client-phone'];
                        }
                            ?>
                    <?php
                    } else {
                        //nie masz juz do tego dostępu
                    }
                } else {
                        ?>
                <td class='p-2 col-12 col-lg-<?= $config['columns']['Imię i nazwisko'] ?>'
                    data-tbl-title="name">#<?= $row['meet_id'] ?> <?= $row['meet_client-name'] ?>
                    <?php
                                $phone = $row['meet_client-phone'];
                            }
                            switch ($row['meet_status']) {
                                case 'wait-answer':
                                ?>
                    <span class='badge bg-primary '><i class="pe-1 fas fa-flag"></i>odpowiedź</span>
                    <?php
                                    break;
                                case 'finished':
                                ?>
                    <span class='badge bg-success '><i class="pe-1 fas fa-flag"></i>Zakończone</span>
                    <?php
                                    break;
                                case 'cancelled':
                                ?>
                    <span class='badge bg-danger '><i class="pe-1 fas fa-flag"></i>Anulowane</span>
                    <?php
                                    break;
                                case 'delayed':
                                ?>
                    <span class='badge bg-info '><i class="pe-1 fas fa-flag"></i>Przełożone</span>
                    <?php
                                    break;
                            }
                            ?>
                </td>
                <?php
                            if ($row['meet_build-id'] != null) {
                                if ($details['group_id'] != null) {
                                    $ans_group = array();
                                    foreach ($group_det as $key => $value) {
                                        $ans_group[] = $key;
                                    }
                                    $group_users = implode("','", $ans_group);
                                } else {
                                    $group_users = $_SESSION['user'];
                                }
                                $select_build = $conn_data->prepare("SELECT `build_town`,`build_road`,`build_house-number`,`build_local-number`,`user_id`FROM `build` WHERE `user_id` IN ('{$group_users}')  AND `build_id`=?;  ");
                                $select_build->execute([$row['meet_build-id']]);
                                $build = $select_build->fetch();
                                if ($build['build_local-number'] != NULL) {
                                    $address = $build['build_town'] . " " . $build['build_road'] . " " . $build['build_house-number'] . "/" . $build['build_local-number'];
                                } else {

                                    $address = $build['build_town'] . " " . $build['build_road'] . " " . $build['build_house-number'];
                                }
                            ?>
                <td class='p-2 col-12 col-lg-<?= $config['columns']['Adres'] ?>'
                    data-tbl-title="address"><a target="_blank"
                       href='https://www.google.com/maps/search/?api=1&query=<?= $address ?>'><?= $address ?><i
                           class="ps-1 fas fa-map-marked-alt"></i></a>
                    <?php
                                    if ($build['user_id'] != $_SESSION['user']) {
                                    ?>
                    <p class='text-link'>Agent <?= $group_det[$build['user_id']] ?></p>
                    <?php
                                    }
                                    ?>
                </td>

                <?php
                            } elseif ($row['meet_build-group-id'] != null) {
                                if ($details['group_id'] != null) {
                                    $ans_group = array();
                                    foreach ($group_det as $key => $value) {
                                        $ans_group[] = $key;
                                    }
                                    $group_users = implode("','", $ans_group);

                                    $select_build = $conn_data->prepare("SELECT `build_town`,`build_road`,`build_house-number`,`build_local-number`,`user_id` FROM `build` WHERE `user_id` IN ('{$group_users}') AND `build_id`=?;  ");
                                    $select_build->execute([$row['meet_build-group-id']]);
                                    $build = $select_build->fetch();
                                    if ($build['build_local-number'] != NULL) {
                                        $address = $build['build_town'] . " " . $build['build_road'] . " " . $build['build_house-number'] . "/" . $build['build_local-number'];
                                    } else {

                                        $address = $build['build_town'] . " " . $build['build_road'] . " " . $build['build_house-number'];
                                    }
                                ?>
                <td class='p-2 col-12 col-lg-<?= $config['columns']['Adres'] ?>'
                    data-tbl-title="address"><a target="_blank"
                       href='https://www.google.com/maps/search/?api=1&query=<?= $address ?>'><?= $address ?><i
                           class="ps-1 fas fa-map-marked-alt"></i></a>
                    <?php
                                        if ($build['user_id'] != $_SESSION['user']) {
                                        ?>
                    <p class='text-link'>Agent <?= $group_det[$build['user_id']] ?></p>
                    <?php
                                        }
                                        ?>
                </td>

                <?php
                                } else {
                                ?>
                <td class='p-2 col-12 col-lg-<?= $config['columns']['Adres'] ?>'
                    data-tbl-title="address">Błąd uprawnień</td>
                <?php
                                    //nie masz juz do tego dostępu
                                }
                            } else {
                                ?>
                <td class='p-2 col-12 col-lg-<?= $config['columns']['Adres'] ?>'
                    data-tbl-title="address"><a target="_blank"
                       href='https://www.google.com/maps/search/?api=1&query=<?= $row['meet_build-address'] ?>'><?= $row['meet_build-address'] ?><i
                           class="ps-1 fas fa-map-marked-alt"></i></a></td>
                <?php
                            }

                            ?>

                <td class='p-2 col-12 col-lg-<?= $config['columns']['Telefon'] ?>'
                    data-tbl-title="phone"><a href="tel:<?= $phone ?>"><?= $phone ?><i
                           class="ps-1 fas fa-phone"></i></a></td>
                <td class='p-2 col-12 col-lg-<?= $config['columns']['Data spotkania'] ?>'
                    data-tbl-title="when"><?= $row['meet_date'] ?> <?= substr($row['meet_time'], 0, 5) ?></td>
                <td class='p-2 col-12 col-lg-<?= $config['columns']['Typ spotkania'] ?>'
                    data-tbl-title="when"><?= str_replace("-", " ", $row['meet_type']) ?></td>
                <td class='p-2 col-12 col-lg-<?= $config['columns']['Przyciski'] ?>'>
                    <div class=" d-flex justify-content-around"
                         data-tbl-title="buttons">
                        <?php
                                    if ($row['user_id'] == $_SESSION['user']) {
                                    ?>
                        <button type="button"
                                class="btn btn-sm btn-primary mx-2"
                                data-id="<?= $row['meet_id'] ?>"
                                data-toggle='modal'
                                data-content="add-meet"
                                data-modal="1"
                                data-title="Edycja spotkania o ID #<?= $row['meet_id'] ?>"
                                <?php
                                                                                                                                                                                                                                                            if ($row['meet_client-phone'] != null) {
                                                                                                                                                                                                                                                            ?>
                                data-call="<?= $row['meet_client-phone'] ?>"
                                <?php
                                                                                                                                                                                                                                                            }
                                                    ?>
                                data-bs-tooltip="tooltip"
                                data-bs-placement="top"
                                title="Edycja">
                            <i class="fas fa-edit"></i><span>Edytuj</span>
                        </button>
                        <?php
                                    }
                                    ?>



                        <button type="button"
                                class="btn btn-sm btn-primary mx-2"
                                data-toggle='modal'
                                data-content="add-meet"
                                data-modal="2"
                                data-title="Szczegóły spotkania o ID #<?= $row['meet_id'] ?>"
                                <?php
                                                                                                                                                                                                                        if ($row['meet_client-phone'] != null) {
                                                                                                                                                                                                                        ?>
                                data-call="<?= $row['meet_client-phone'] ?>"
                                <?php
                                                                                                                                                                                                                        }
                                                    ?>
                                data-id="<?= $row['meet_id'] ?>"
                                data-bs-tooltip="tooltip"
                                data-bs-placement="top"
                                title="Informacje">
                            <i class="fas fa-info-circle"></i><span>Informacje</span>
                        </button>
                        <!-- <button type="button"
                                class="btn btn-sm btn-primary mx-2"
                                data-toggle="modal"
                                data-modal='14'
                                data-content="add-build"
                                data-title="Szczegóły nieruchomości o ID #<?= $row['build_id'] ?>"
                                data-id="<?= $row['build_id'] ?>"
                                data-call="<?= $row['build_client-phone'] ?>"
                                data-bs-tooltip="tooltip"
                                data-bs-placement="top"
                                title="Informacje">
                            <i class="fas fa-info-circle"></i><span>TEST</span>
                        </button> -->
                    </div>
                </td>
                <td class="col-12 order-2 order-lg-3 col-lg-12">

                    <div class="d-grid w-100 ">
                        <div class="row w-100 justify-content-around m-1">


                        </div>
                    </div>
                </td>

            </tr>
        </table>
    </td>
</tr>