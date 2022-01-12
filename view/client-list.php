<tr data-id='<?= $row['client_id'] ?>'>
    <td colspan='5'
        class='p-0'>
        <table class='w-100'>
            <tr class='d-flex flex-wrap align-items-center'
                data-id='<?= $row['client_id'] ?>'>
                <?php
if($row['user_id'] == $_SESSION['user']){
?>
                <td class='p-2 col-12 col-lg-<?=$config['columns']['Imię i nazwisko']?>'
                    data-tbl-title="name">#<?= $row['client_id'] ?> <?= $row['client_client-name'] ?>
                    <?= $row['client_client-surname'] ?></td>
                <td class='p-2 col-12 col-lg-<?=$config['columns']['Telefon']?>'
                    data-tbl-title="phone"><a
                       href="tel:<?= $row['client_client-phone'] ?>"><?= $row['client_client-phone'] ?><i
                           class="ps-1 fas fa-phone"></i></a></td>
                <td class='p-2 col-12 col-lg-<?=$config['columns']['Dodano']?>'
                    data-tbl-title="when"><?= $row['client_create-date'] ?></td>
                <td class='p-2 col-12  order-3 order-lg-2  col-lg-<?=$config['columns']['Przyciski']?>'>
                    <div class="p-2 d-flex justify-content-around"
                         data-tbl-title="buttons">
                        <button type="button"
                                class="btn btn-sm btn-primary mx-2"
                                data-id="<?= $row['client_id'] ?>"
                                data-toggle='modal'
                                data-content="add-client"
                                data-modal='1'
                                data-title="Edycja klienta o ID #<?= $row['client_id'] ?>"
                                data-call="<?= $row['client_client-phone'] ?>"
                                data-bs-tooltip="tooltip"
                                data-bs-placement="top"
                                data-moderate="TRUE"
                                title="Edycja">
                            <i class="fas fa-edit"></i><span>Edytuj</span>
                        </button>
                        <button type="button"
                                class="btn btn-sm btn-primary mx-2"
                                data-toggle='modal'
                                data-content="add-client"
                                data-modal='2'
                                data-id="<?= $row['client_id'] ?>"
                                data-title="Szczegóły klienta o ID #<?= $row['client_id'] ?>"
                                data-call="<?= $row['client_client-phone'] ?>"
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
                <?php
}else{
?>
                <td class='p-2 col-12 col-lg-<?=$config['columns']['Imię i nazwisko']?>'
                    data-tbl-title="name"><i class="fas fa-external-link-alt pe-2"></i>#<?= $row['client_id'] ?> 
                    <?php
$user = $conn_user->prepare("SELECT `name`,`surname` FROM `users` WHERE `uid` = ?");
$user -> execute([$row['user_id']]);
$this_user = $user->fetch();
                    ?>
                    <i> Agent <?=$this_user['name']?> <?=$this_user['surname']?></i>
                 </td>
                <td class='p-2 col-12 col-lg-<?=$config['columns']['Telefon']?>'
                    data-tbl-title="phone"><i>Telefon ukryty</i></td>
                <td class='p-2 col-12 col-lg-<?=$config['columns']['Dodano']?>'
                    data-tbl-title="when"><?= $row['client_create-date'] ?></td>
                <td class='p-2 col-12  order-3 order-lg-2  col-lg-<?=$config['columns']['Przyciski']?>'>
                    <div class="p-2 d-flex justify-content-around"
                         data-tbl-title="buttons">
                        <button type="button"
                                class="btn btn-sm btn-primary mx-2"
                                data-toggle='modal'
                                data-content="add-client"
                                data-modal='16'
                                data-id="<?= $row['client_id'] ?>"
                                data-title="Klient <?=$this_user['name']?> <?=$this_user['surname']?> #<?= $row['client_id'] ?> "
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
                <?php
}
                ?>
                <td class='col-12 order-2 order-lg-3 col-lg-12'>

                    <?php

include(__DIR__.'/prev-col.php');
?>
                </td>

        </table>
    </td>
</tr>