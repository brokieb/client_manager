<?php
include('../default.php');
$session = new sessionManage();
$det = new accountDetails();
$select = $conn_data->prepare("SELECT * FROM `build` WHERE `user_id`={$_SESSION['user']} {$demo} AND `build_id`=?;   ");
$select->execute([$_POST['id']]);
$d = $select->fetch();
?>
<div class='container pb-4 printable'>
    <div class='row justify-content-between pb-2'>
        <span class='col text-nowrap'>Utw: <?= $d['build_create-date'] ?></span>
        <span class='col text-nowrap text-center'>User: <?= $det->accountData()['username'] ?> #<?=$det->accountData()['uid']?></span>
        <span class='col text-nowrap text-end'>Gen: <?= date("Y-m-d H:m:i") ?></span>
    </div>
    <div class='row justify-content-center'>
        <h2 class='text-center'>OPIS NIERUCHOMOŚCI O ID #<?= $_POST['id'] ?></h2>
    </div>
    <div class='row justify-content-center'>
    <h3 class="text-center">
        <?= $d['build_town'] ?> <?= $d['build_road'] ?> <?= $d['build_house-number'] ?>
        <?php
        if ($d['build_local-number'] != "") {
            ?>
/ <?=$d['build_local-number']?>
            <?php
        }
        ?>
        </h3 class="text-center">
    </div>
    <div class='row justify-content-center'>
    <h3 class="text-center">
        <?= $d['build_client-name'] ?> <?= $d['build_client-surname'] ?>
        </h3 class="text-center">
    </div>
    <div class='row justify-content-center'>
        <?= $d['build_client-phone'] ?>
    </div>
    <div class='row justify-content-center'>
        <?= $d['build_client-email'] ?>
    </div>
    <hr>
    <div class='row justify-content-center'>
        <span class='text-center'><b><?= $d['build_type-of-building'] ?></b> cel: <?= $d['build_purpose'] ?></span>
    </div>
    <div class='row justify-content-between'>
        <div class='col-6'>
            <h4 class='text-center print-title'>Budynek</h4>
            <table>
                <tbody>
                    <?php
                    $select_c = $conn_form->query("SELECT `group_name`,`group_title`,`group_id`,`group_type` FROM `form-group` WHERE `group_form`='add-build' AND `group_category`='build'; ");
                    while ($c = $select_c->fetch()) {
                        switch($c['group_type']){
                            case 'textarea':
                            case 'text':
                                $select_f = $conn_form->query("SELECT `field_name`,`field_title`,`field_unit` FROM `form-field` WHERE `group_id`='".$c['group_id']."';  ");
                                while ($f = $select_c->fetch()) {

if (isset($d[$f['field_name']])) {
                    ?>
                            <tr>
                                <td class="fw-bold text-end py-1 px-2"><?= $f['field_title'] ?></td>
                                <td><?= str_replace(array("|","-"),array(","," "),$d[$f['field_name']]) ?>
                            <?php
if($f['field_unit']!=null){
    echo str_replace("m^2"," m<sup>2</sup>",$f['field_unit']);
}
                            ?></td>
                            </tr>
                    <?php
                        }
                                }
                                break;
                            default:
                           
                        if (isset($d[$c['group_name']])) {
                    ?>
                            <tr>
                                <td class="fw-bold text-end py-1 px-2"><?= $c['group_title'] ?></td>
                                <td><?= str_replace(array("|","-"),array(","," "),$d[$c['group_name']]) ?></td>
                            </tr>
                    <?php
                        }
                        break;
                    }
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <div class='col-6'>
            <h4 class='text-center print-title'>Lokal</h4>
            <table>
                <tbody>
                    <?php
                    $select_c = $conn_form->query("SELECT `group_name`,`group_title`,`group_id`,`group_type` FROM `form-group` WHERE `group_form`='add-build' AND `group_category`='flat'; ");
                    while ($c = $select_c->fetch()) {
                        switch($c['group_type']){
                            case 'textarea':
                            case 'text':
                                $select_f=$conn_form->query("SELECT `field_name`,`field_title`,`field_unit` FROM `form-field` WHERE `group_id`='{$c['group_id']}';  ");
                            while ($f = $select_f->fetch()) {

if (isset($d[$f['field_name']])) {
                    ?>
                            <tr>
                                <td class="fw-bold text-end py-1 px-2"><?= $f['field_title'] ?></td>
                                <td><?= str_replace(array("|","-"),array(","," "),$d[$f['field_name']]) ?><?php
if($f['field_unit']!=null){
    echo str_replace("m^2"," m<sup>2</sup>",$f['field_unit']);
}
                            ?></td>
                            </tr>
                    <?php
                        }
                                }
                                break;
                            default:
                           
                        if (isset($d[$c['group_name']])) {
                    ?>
                            <tr>
                                <td class="fw-bold text-end py-1 px-2"><?= $c['group_title'] ?></td>
                                <td><?= str_replace(array("|","-"),array(","," "),$d[$c['group_name']]) ?></td>
                            </tr>
                    <?php
                        }
                        break;
                    }
                    }

                    ?>
                </tbody>
            </table>
        </div>
    </div>

<div class='row justify-content-between'>
    <div class='col-6'>
        <h4 class='text-center print-title'>Remont / wykończenie</h4>
        <table>
            <tbody>
                <?php
                $select_c = $conn_form->query("SELECT `group_name`,`group_title`,`group_id`,`group_type`FROM `form-group`  WHERE `group_form`='add-build' AND `group_category`='finish'; ");
                $select_c->execute();
                while ($c = $select_c->fetch()) {
                    switch($c['group_type']){
                        case 'textarea':
                        case 'text':
                            $select_f = $conn_form->query("SELECT `field_name`,`field_title` FROM `form-field` WHERE `group_id`='{$c['group_id']}';  ");
                            while ($f = $select_f->fetch()) {

if (isset($d[$f['field_name']])) {
                ?>
                        <tr>
                            <td class="fw-bold text-end py-1 px-2"><?= $f['field_title'] ?></td>
                            <td><?= str_replace(array("|","-"),array(","," "),$d[$f['field_name']]) ?><?php
if($f['field_unit']!=null){
    echo str_replace("m^2"," m<sup>2</sup>",$f['field_unit']);
}
                            ?></td>
                        </tr>
                <?php
                    }
                            }
                            break;
                        default:
                       
                    if (isset($d[$c['group_name']])) {
                ?>
                        <tr>
                            <td class="fw-bold text-end py-1 px-2"><?= $c['group_title'] ?></td>
                            <td><?= str_replace(array("|","-"),array(","," "),$d[$c['group_name']]) ?></td>
                        </tr>
                <?php
                    }
                    break;
                }
                }
                ?>
            </tbody>
        </table>
    </div>
    <div class='col-6'>
        <h4 class='text-center print-title'>Informacje dodatkowe</h4>
        <table>
            <tbody>
                <?php
                $select_c = $conn_form->query("SELECT `group_name`,`group_title`,`group_id`,`group_type` FROM `form-group` WHERE `group_form`='add-build' AND `group_category`='additional'; ");
                while ($c = $select_c->fetch()) {
                    switch($c['group_type']){
                        case 'textarea':
                        case 'text':
                            $select_f = $conn_form->query("SELECT `field_name`,`field_title`,`field_unit` FROM `form-field` WHERE `group_id`='{$c['group_id']}';  ");
                            while ($f = $select_f->fetch()) {

if (isset($d[$f['field_name']])) {
                ?>
                        <tr>
                            <td class="fw-bold text-end py-1 px-2"><?= $f['field_title'] ?></td>
                            <td><?= str_replace(array("|","-"),array(","," "),$d[$f['field_name']]) ?><?php
if($f['field_unit']!=null){
    echo str_replace("m^2"," m<sup>2</sup>",$f['field_unit']);
}
                            ?></td>
                        </tr>
                <?php
                    }
                            }
                            break;
                        default:
                       
                    if (isset($d[$c['group_name']])) {
                ?>
                        <tr>
                            <td class="fw-bold text-end py-1 px-2"><?= $c['group_title'] ?></td>
                            <td><?= str_replace(array("|","-"),array(","," "),$d[$c['group_name']]) ?></td>
                        </tr>
                <?php
                    }
                    break;
                }
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
</div>
</div>
<p class='print-preview'>Druk wygenerowany, kliknij w przycisk poniżej żeby kontynuować</p>