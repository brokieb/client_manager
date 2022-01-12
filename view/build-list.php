<tr data-id='<?= $row['build_id'] ?>'>
    <td colspan='6'
        class='p-0'>
        <table class='w-100'>
            <tr class='d-flex flex-wrap align-items-center'>
                <!-- OWN -->
                <?php
if($row['user_id'] == $_SESSION['user']){
?>
                <td class='p-2 col-12 col-lg-<?=$config['columns']['Imię i nazwisko']?>'
                    data-tbl-title="name">#<?= $row['build_id'] ?> <?= $row['build_client-name'] ?>
                    <?= $row['build_client-surname'] ?></td>
                <?php
                
if($row['build_local-number']==NULL){ 
    ?>
                <td class='p-2 col-12 col-lg-<?=$config['columns']['Adres']?>'
                    data-tbl-title="address"><a target="_blank"
                       href="https://www.google.com/maps/search/?api=1&query=<?= $row['build_road'] ?> <?= $row['build_house-number'] ?>"><?= $row['build_road'] ?>
                        <?= $row['build_house-number'] ?>
                        <?php
}else{
   ?>
                <td class='p-2 col-12 col-lg-<?=$config['columns']['Adres']?>'
                    data-tbl-title="address"><a target="_blank"
                       href="https://www.google.com/maps/search/?api=1&query=<?= $row['build_road'] ?> <?= $row['build_house-number'] ?>/<?= $row['build_local-number'] ?>"><?= $row['build_road'] ?>
                        <?= $row['build_house-number'] ?>/<?= $row['build_local-number'] ?>
                        <?php
}

?>

                        <i class="ps-1 fas fa-map-marked-alt"></i></a></td>
                <td class=' p-2 col-12 col-lg-<?=$config['columns']['Telefon']?>'
                    data-tbl-title="phone"><a
                       href="tel:<?= $row['build_client-phone'] ?>"><?= $row['build_client-phone'] ?><i
                           class="ps-1 fas fa-phone"></i></a></td>
                <td class=' p-2 col-12 col-lg-<?=$config['columns']['Dodano']?>'
                    data-tbl-title="when"><?= $row['build_create-date'] ?></td>
                <td class=' p-2 col-12 order-3 order-lg-2 col-lg-<?=$config['columns']['Przyciski']?>'>
                    <div class='p-2 d-flex justify-content-around'
                         data-tbl-title="buttons">


                        <button type="button"
                                class="btn btn-sm btn-primary mx-2"
                                data-toggle="modal"
                                data-content="add-build"
                                data-modal='1'
                                data-id="<?= $row['build_id'] ?>"
                                data-call="<?= $row['build_client-phone'] ?>"
                                data-title="Edycja nieruchomości o ID #<?= $row['build_id'] ?>"
                                data-bs-tooltip="tooltip"
                                data-bs-placement="top"
                                title="Edycja">
                            <i class="fas fa-edit"></i><span>Edytuj</span>
                        </button>
                        <button type="button"
                                class="btn btn-sm btn-primary mx-2"
                                data-toggle="modal"
                                data-modal='2'
                                data-content="add-build"
                                data-title="Szczegóły nieruchomości o ID #<?= $row['build_id'] ?>"
                                data-id="<?= $row['build_id'] ?>"
                                data-call="<?= $row['build_client-phone'] ?>"
                                data-bs-tooltip="tooltip"
                                data-bs-placement="top"
                                title="Informacje">
                            <i class="fas fa-info-circle"></i><span>Informacje</span>
                        </button>
                        <?php /* 
                        <button type="button"
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
                        </button>
                         */ ?>
                        <button type="button"
                                class="btn btn-sm btn-primary mx-2"
                                data-toggle="modal"
                                data-modal='3'
                                data-title="Drukowanie nieruchomości o ID #<?= $row['build_id'] ?>"
                                data-id="<?= $row['build_id'] ?>"
                                data-site="print-pages/print-build"
                                data-bs-tooltip="tooltip"
                                data-bs-placement="top"
                                title="Drukuj">
                            <i class="fas fa-print"></i><span>Drukuj</span>
                        </button>
                    </div>
                </td>
                <!-- </OWN> -->
                <?php
}else{
?>
                <!-- FOREIGN -->
                <td class='p-2 col-12 col-lg-<?=$config['columns']['Imię i nazwisko']?>'
                    data-tbl-title="name"><i class="fas fa-external-link-alt pe-2"></i>#<?= $row['build_id'] ?> 
                    <?php
$user = $conn_user->prepare("SELECT `name`,`surname` FROM `users` WHERE `uid` = ?");
$user -> execute([$row['user_id']]);
$this_user = $user->fetch();
                    ?>
                    <i> Agent <?=$this_user['name']?> <?=$this_user['surname']?></i>
                 </td>
                <?php
if($row['build_local-number']==NULL){ 
    ?>
                <td class='p-2 col-12 col-lg-<?=$config['columns']['Adres']?>'
                    data-tbl-title="address"><a target="_blank"
                       href="https://www.google.com/maps/search/?api=1&query=<?= $row['build_road'] ?> <?= $row['build_house-number'] ?>"><?= $row['build_road'] ?>
                        <?= $row['build_house-number'] ?>
                        <?php
}else{
   ?>
                <td class='p-2 col-12 col-lg-<?=$config['columns']['Adres']?>'
                    data-tbl-title="address"><a target="_blank"
                       href="https://www.google.com/maps/search/?api=1&query=<?= $row['build_road'] ?> <?= $row['build_house-number'] ?>/<?= $row['build_local-number'] ?>"><?= $row['build_road'] ?>
                        <?= $row['build_house-number'] ?>/<?= $row['build_local-number'] ?>
                        <?php
}

?>

                        <i class="ps-1 fas fa-map-marked-alt"></i></a></td>
                <td class=' p-2 col-12 col-lg-<?=$config['columns']['Telefon']?>'
                    data-tbl-title="phone"><i>Telefon ukryty</i></td>
                <td class=' p-2 col-12 col-lg-<?=$config['columns']['Dodano']?>'
                    data-tbl-title="when"><?= $row['build_create-date'] ?></td>
                <td class=' p-2 col-12 order-3 order-lg-2 col-lg-<?=$config['columns']['Przyciski']?>'>
                    <div class='p-2 d-flex justify-content-around'
                         data-tbl-title="buttons">
                        <button type="button"
                                class="btn btn-sm btn-primary mx-2"
                                data-toggle="modal"
                                data-modal='2'
                                data-content="add-build"
                                data-title="Nieruchomość #<?= $row['build_id'] ?> "
                                data-id="<?= $row['build_id'] ?>"
                                data-bs-tooltip="tooltip"
                                data-bs-placement="top"
                                title="Informacje">
                            <i class="fas fa-info-circle"></i><span>Informacje</span>
                        </button>
                        <?php /* 
                        <button type="button"
                                class="btn btn-sm btn-primary mx-2"
                                data-toggle="modal"
                                data-modal='14'
                                data-content="add-build"
                                data-title="Szczegóły nieruchomości o ID #<?= $row['build_id'] ?>"
                                data-id="<?= $row['build_id'] ?>"
                                data-bs-tooltip="tooltip"
                                data-bs-placement="top"
                                title="Informacje">
                            <i class="fas fa-info-circle"></i><span>TEST</span>
                        </button>
                          */ ?>
                        <button type="button"
                                class="btn btn-sm btn-primary mx-2"
                                data-toggle="modal"
                                data-modal='3'
                                data-title="Drukowanie nieruchomości o ID #<?= $row['build_id'] ?>"
                                data-id="<?= $row['build_id'] ?>"
                                data-site="print-pages/print-build"
                                data-bs-tooltip="tooltip"
                                data-bs-placement="top"
                                title="Drukuj">
                            <i class="fas fa-print"></i><span>Drukuj</span>
                        </button>
                    </div>
                </td>
                <?php
}
                ?>
                <!-- </FOREIGN> -->
                <td colspan='8'
                    class='col-12 order-2 order-lg-3 col-lg-12'>
                    <?php

include(__DIR__.'/prev-col.php');
?>
                </td>
            </tr>
        </table>
    </td>
</tr>