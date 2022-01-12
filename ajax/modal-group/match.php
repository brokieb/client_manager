<?php
include('../default.php');
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
if ($det->checkAllowed($content, $_POST['contentId']) > 0) {
    $keys = implode("','", array_keys($group_det));
    $build_select = $conn_data->prepare("SELECT * FROM `{$content}` WHERE `user_id` IN ('{$keys}') AND `{$content}_id`= ? ");
    $build_select->execute([$_POST['contentId']]);
    $build = $build_select->fetch(PDO::FETCH_ASSOC);
    $where = array();

    switch ($content) {
        case 'client':

            if ($build['property_type-of-building'] != null) { //rodzaj lokalizacji
                $where[] = " `build_type-of-building` IN('" . str_replace(" | ", "','", $build['property_type-of-building']) . "') ";
            }
            if ($build['property_region'] != null) { // lokalizacja
                $where[] = " `build_region` IN('" . str_replace(" | ", "','", $build['property_region']) . "') ";
            }
            if ($build['property_living-area-from'] != null || $build['property_living-area-to'] != null) { // metraż
                if ($build['property_living-area-from'] == null) {
                    $from = 0;
                } else {
                    $from = $build['property_living-area-from'];
                }

                if ($build['property_living-area-to'] == null) {
                    $to = 9999;
                } else {
                    $to = $build['property_living-area-to'];
                }

                $where[] = " `build_living-area` BETWEEN {$from} AND {$to} ";
            }

            if ($build['property_floor-from'] != null || $build['property_floor-to'] != null) { //piętro
                if ($build['property_floor-from'] == null) {
                    $from = 0;
                } else {
                    $from = $build['property_floor-from'];
                }

                if ($build['property_floor-to'] == null) {
                    $to = 20;
                } else {
                    $to = $build['property_floor-to'];
                }

                $where[] = " `build_floor`BETWEEN {$from} AND {$to} ";
            }

            if ($build['property_rooms-from'] != null || $build['property_rooms-to'] != null) { //pokoje
                if ($build['property_rooms-from'] == null) {
                    $from = 0;
                } else {
                    $from = $build['property_rooms-from'];
                }

                if ($build['property_rooms-to'] == null) {
                    $to = 99;
                } else {
                    $to = $build['property_rooms-to'];
                }

                $where[] = " `build_rooms` BETWEEN {$from} AND {$to} ";
            }

            if ($build['property_max-price'] != null) { //cena nieruchomości
                $to = $build['property_max-price'];
                $where[] = " `build_price` <= '{$to}' ";
            }

            break;
        case 'build':
            if ($build['build_type-of-building'] != null) { //rodzaj lokalizacji

                $where[] = " `property_type-of-building` LIKE '%" . $build['build_type-of-building'] . "%' ";
            }
            if ($build['build_region'] != null) { // lokalizacja
                $where[] = " `property_region` LIKE '%" . $build['build_region'] . "%' ";
            }
            if ($build['build_living-area'] != null) { // metraż
                $where[] = " ( `property_living-area-from`<={$build['build_living-area']} OR `property_living-area-from` IS NULL ) AND ( `property_living-area-to`>={$build['build_living-area']} OR `property_living-area-from` IS NULL ) ";
            }

            if ($build['build_floor'] != null) { //piętro
                $where[] = " ( `property_floor-from`<={$build['build_floor']} OR `property_floor-from` IS NULL ) AND ( `property_floor-to`>={$build['build_floor']} OR `property_floor-to` IS NULL ) ";
            }

            if ($build['build_rooms'] != null) { //pokoje
                $where[] = " ( `property_rooms-from`<={$build['build_rooms']} OR `property_rooms-from` IS NULL ) AND ( `property_rooms-to`>={$build['build_rooms']} OR `property_rooms-to` IS NULL ) ";
            }

            if ($build['build_price'] != null) { //cena nieruchomości
                $to = str_replace(" ", "", $build['build_price']);
                $where[] = " `property_max-price` >= '{$to}' ";
            }
            break;
    }

    if (!empty($where)) {

        $where[] = " `matches`.`{$content_reverse}_id` IS NULL ";
        $rules = implode(" AND ", $where);
        if ($build[$content . '_id'] != $_SESSION['user']) {
            $user = "`{$content_reverse}`.`user_id`={$_SESSION['user']}";
        } elseif ($details['group_id'] != null) {
            $user = "`{$content_reverse}`.`user_id` IN ('" . implode("' , '", array_keys($group_det)) . "')";
        } else {
            $user = "`{$content_reverse}`.`user_id`={$_SESSION['user']}";
        }

        $select = $conn_data->query("SELECT `{$content_reverse}`.`user_id`,`{$content_reverse}`.`{$content_reverse}_id` as 'id',`{$content_reverse}_client-name` as 'name',`{$content_reverse}_client-surname` as 'surname',`{$content_reverse}_client-phone` as 'phone',`{$content_reverse}_comment` as 'comment'  FROM {$content_reverse} LEFT JOIN `matches` ON `{$content_reverse}`.`{$content_reverse}_id` = `matches`.`{$content_reverse}_id` WHERE {$rules} AND {$user} ORDER BY `{$content_reverse}`.`user_id` = {$_SESSION['user']} DESC");
        if ($select->rowCount() > 0) {
?>
<div class="alert alert-info"
     role="alert">
    Możesz tutaj zobaczyć klientów którzy zostali przypisani na podstawie preferencji zapisanych przy klientach.
    Aplikacja paruje nieruchomości z klientami na podstawie poniższych parametrów:
    <ul>
        <li>Rodzaj nieruchomości (dom , mieszkanie, działka)</li>
        <li>Lokalizacja</li>
        <li>Piętro</li>
        <li>Liczba pokoi</li>
        <li>Metraż</li>
    </ul>
    Dopasowane pozycje powinny być odpowiednio przejrzane i oznaczone. W sytuacji kiedy przeanalizowaliśmy daną
    propozycję możemy oznaczyć klienta jako wstępnie zainteresowanego lub odrzucić propozycję - klient nie będzie się
    już wyświetlał jako propozycja dla TEJ nieruchomości
</div>

<table class='table table-bordered'>
    <thead>
        <tr>
            <th scope="col"
                class='flex-nowrap'>ID</th>
            <th scope="col"
                class='flex-nowrap'>Imię i nazwisko</th>
            <th scope="col"
                class='flex-nowrap'>Notatki</th>
            <th scope="col"
                class='flex-nowrap'>Przyciski</th>
        </tr>
    </thead>
    <tbody>
        <?php
                    while ($row = $select->fetch(PDO::FETCH_ASSOC)) {
                        if ($row['user_id'] == $_SESSION['user']) {
                    ?>
        <tr data-match-id="<?= $row['id'] ?>">
            <td><?= $row['id'] ?></td>
            <td style="white-space:nowrap;"><?= $row['name'] ?> <?= $row['surname'] ?></td>
            <td><i><?= $row['comment'] ?></i></td>
            <td>
                <div class='d-flex flex-wrap justify-content-around'
                     data-tbl-title="buttons">


                    <button type='button'
                            class="btn btn-sm btn-primary m-1 match-content"
                            data-match-type="1"
                            data-foreign-id="<?= $row['id'] ?>"
                            data-main-id="<?= $_POST['contentId'] ?>"
                            data-content="<?= $content ?>"
                            data-bs-tooltip="tooltip"
                            title="Zaakceptuj propozycję">
                        <i class="far fa-check-circle"></i><span>Zapisz na później</span>
                    </button>

                    <button type="button"
                            class="btn btn-sm btn-primary m-1"
                            data-toggle='modal'
                            data-content="add-<?= $content_reverse ?>"
                            data-modal='2'
                            data-id="<?= $row['id'] ?>"
                            data-title="<?= $modal_title ?> #<?= $row['id'] ?>"
                            data-call="<?= $row['phone'] ?>"
                            data-bs-tooltip="tooltip"
                            data-bs-placement="top"
                            title="Informacje">
                        <i class="fas fa-info-circle"></i><span>Informacje</span>
                    </button>
                    <?php
                                        if ($row['user_id'] == $_SESSION['user']) {
                                        ?>
                    <a href="tel:<?= $row['phone'] ?>"
                       class="btn btn-sm btn-primary m-1"
                       data-bs-tooltip="tooltip"
                       title="Zadzwoń do klienta"><i class="fas fa-phone"></i><span>Zadzwoń</span>
                    </a>
                    <button type="button"
                            class="btn btn-sm btn-primary m-1"
                            data-toggle="modal"
                            data-content="add-<?= $content_reverse ?>"
                            data-modal='1'
                            data-id="<?= $row['id'] ?>"
                            data-call="<?= $row['phone'] ?>"
                            data-title="<?= $modal_title ?>"
                            data-bs-tooltip="tooltip"
                            data-bs-placement="top"
                            title="Edycja">
                        <i class="fas fa-edit"></i><span>Edytuj</span>
                    </button>
                    <?php
                                        }
                                        ?>
                    <button type='button'
                            class="btn btn-sm btn-primary m-1 match-content"
                            data-match-type="0"
                            data-foreign-id="<?= $row['id'] ?>"
                            data-main-id="<?= $_POST['contentId'] ?>"
                            data-content="<?= $content ?>"
                            data-bs-tooltip="tooltip"
                            title="Odrzuć propozycję"><i class="fas fa-ban"></i><span>Odrzuć tą propozycję</span>
                    </button>
                </div>
            </td>
        </tr>
        <?php
                        } else {
                        ?>
        <tr data-match-id="<?= $row['id'] ?>">
            <td><?= $row['id'] ?></td>
            <td style="white-space:nowrap;"><?= $row['name'] ?> <?= $row['surname'] ?></br>
                <p class='text-danger text-bold m-0'>
                    <?php
                                        $this_details = $conn_user->prepare("SELECT `name`,`surname` FROM `users` WHERE `uid` = ?");
                                        $this_details->execute([$row['user_id']]);
                                        $details_row = $this_details->fetch();
                                        ?>
                    agent <?= $details_row['name'] ?> <?= $details_row['surname'] ?></p>
            </td>
            <td><i><?= $row['comment'] ?></i></td>
            <td>
                <div class='d-flex flex-wrap justify-content-around'
                     data-tbl-title="buttons">
                    <button type='button'
                            class="btn btn-sm btn-primary m-1 match-content"
                            data-match-type="1"
                            data-foreign-id="<?= $row['id'] ?>"
                            data-main-id="<?= $_POST['contentId'] ?>"
                            data-content="<?= $content ?>"
                            data-bs-tooltip="tooltip"
                            title="Zaakceptuj propozycję">
                        <i class="far fa-check-circle"></i><span>Zapisz na później</span>
                    </button>
                    <button type="button"
                            class="btn btn-sm btn-primary m-1"
                            data-toggle='modal'
                            data-content="add-<?= $content_reverse ?>"
                            data-modal='17'
                            data-id="<?= $row['id'] ?>"
                            data-title="<?= $modal_title ?> #<?= $row['id'] ?>"
                            data-call="<?= $row['phone'] ?>"
                            data-bs-tooltip="tooltip"
                            data-bs-placement="top"
                            title="Informacje">
                        <i class="fas fa-info-circle"></i><span>Informacje</span>
                    </button>
                    <button type='button'
                            class="btn btn-sm btn-primary m-1 match-content"
                            data-match-type="0"
                            data-foreign-id="<?= $row['id'] ?>"
                            data-main-id="<?= $_POST['contentId'] ?>"
                            data-content="<?= $content ?>"
                            data-bs-tooltip="tooltip"
                            title="Odrzuć propozycję"><i class="fas fa-ban"></i><span>Odrzuć tą propozycję</span>
                    </button>
                </div>
            </td>
        </tr>
        <?php
                        }
                    }
                    ?>
    </tbody>
</table>
<?php
        } else {
        ?>
<div class="alert alert-info"
     role="alert">
    Nie udało się znaleźć żadnego pasującego klienta do tej nieruchomości. Kryteria które są brane pod uwagę przy
    powiązywaniu klienta to:

    <ul>
        <li>Rodzaj nieruchomości (dom , mieszkanie, działka)</li>
        <li>Lokalizacja</li>
        <li>Piętro</li>
        <li>Liczba pokoi</li>
        <li>Metraż</li>
        <li>Cena nieruchomości</li>
    </ul>
    Uzupełnianie tych danych przy dodawaniu klientów szukających nieruchomości i samych nieruchomości, pozwoli w łatwy
    sposób znaleźć pasujące pozycje w przyszłości.
    Jeżeli jesteś pewien że posiadasz w bazie klienta który pasuje do tej nieruchomości i nie wyświetla się on tutaj -
    proszę o informację na chacie, zweryfikujemy ten problem
</div>

<?php
        }
    } else {
        ?>
<div class="alert alert-info"
     role="alert">
    Przy tej nieruchomości zostało podane za mało argumentów żeby móc dopasować klienta. Dane które są brane pod uwagę
    przy dobieraniu to:
    <ul>
        <li>Rodzaj nieruchomości (dom , mieszkanie, działka)</li>
        <li>Lokalizacja</li>
        <li>Piętro</li>
        <li>Liczba pokoi</li>
        <li>Metraż</li>
        <li>Cena nieruchomości</li>
    </ul>
</div>
<?php
    }
    ?>
<?php
} else {
    include(__DIR__ . '/../../view/show-deny.php');
}
?>