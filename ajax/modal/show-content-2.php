<?php
include('../default.php');
$site = explode("-",$_POST['content']);
$det = new accountDetails();
$select = $conn_data->prepare("SELECT * FROM `{$site[1]}` WHERE {$demo} `{$site[1]}_id`= ? ;");
$select->execute([$_POST['id']]);
$details_content = $select->fetch();
$allow = $det->userGroupAllowed($details_content['user_id']);
if(!$allow){
    include(__DIR__.'/../../view/show-deny.php');
    exit;
}
if($site[1]=='meet'){
    if($details_content['meet_sync']!=null){
        ?>
<span class="p-2 badge bg-success"><i class="far pe-2 fa-check-circle"></i>Spotkanie zsynchronizowane z Google
    Calendar</span>
<?php
    }
}
?>
<div class='d-flex justify-content-center flex-column'>
    <div class='d-flex flex-row justify-content-between flex-wrap print-content'>

        <?php
if($_SESSION['user']!=$details_content['user_id']){
}else{
}
$secured = null;
        $select_g = $conn_form->query("SELECT * FROM `form-group` WHERE `group_form`='add-{$site[1]}' {$secured}  ORDER BY CASE WHEN `group_type` = 'text' then 1 WHEN `group_type` = 'select' then 1 WHEN `group_type` = 'textarea' then 2 else 3 end ASC;");
        $select_g->execute();
        $i = 0;
        $last = 0;
        while ($group = $select_g->fetch()) {
            if ($i == 0) {
                ?>
        <div class='col-lg-6 p-1'>
            <?php
                $i++;
            }
            if ($last != $group['group_type'] && $last == 'textarea') {
                ?>
        </div>
        <div class='col-lg-6 p-1'>
            <?php
            }
            $last = $group['group_type'];


        ?>

            <div class='w-100 m-0 checks-main'>
                <div class='p-1'>
                    <h4 class='m-0'><?= $group['group_title'] ?></h4>
                </div>
                <?php
                $select_r = $conn_form->query("SELECT DISTINCT `group_id` FROM `form-field` WHERE `group_id`={$group['group_id']} ORDER BY `group_id` ;");
                $select_r->execute();
                while ($row = $select_r->fetch()) {

                    switch ($group['group_type']) {
                        case 'select':
                        case 'textarea':
                        case 'text':
                ?>
                <div class="py-2 row checks">
                    <?php
                            break;
                        case 'radio':
                        case 'checkbox':
                            ?>
                    <div class='py-2 d-flex flex-wrap checks'>
                        <?php
                                break;
                        }
                        $select_f = $conn_form->query("SELECT * FROM `form-field` WHERE `group_id`={$row['group_id']} ;");
                        $select_f -> execute();
                        while ($field = $select_f->fetch()) {

                            if ($field['field_name'] == null) {
                                $field['field_name'] = str_replace(" ","-",strtolower($field['field_title']));
                            }

                                ?>

                        <?php
                                switch ($field['field_type']) {
                                    case 'textarea':
                                        if ($details_content[$field['field_name']] != null) {
                                ?>
                        <div class="mb-1 col-<?= $field['row_size'] ?>">
                            <label for="<?= $field['field_name'] ?>"><?= $field['field_title'] ?></label>
                            <textarea class="form-control form-control-sm"
                                      aria-label="With textarea"
                                      readonly><?= $details_content[$field['field_name']] ?></textarea>
                        </div>
                        <?php
                                        }
                                        ?>

                        <?php
                                        break;
                                    case 'select':
                                        ?>
                        <div class="col-<?= $field['row_size'] ?>  mb-1 d-flex flex-column">
                            <?php
    if($details_content[$field['field_name']]!=null){
$position = strpos($field['field_name'],"_");
$table = str_replace("-","_",substr($field['field_name'],$position+1));
$table_noid = explode("_",$table);
switch($table_noid[0]){
    case 'build':
        $title_modal = "Szczegóły nieruchomości o ID #".$details_content[$field['field_name']];
    break;
    case 'client':
        $title_modal = "Szczegóły klienta o ID #".$details_content[$field['field_name']];
    break;
}            
?>

                            <div class="mb-3 select-content col-<?= $field['row_size'] ?>">
                                <div class="mt-1 select-div">

                                    <?php
                                 $sql_vars['user_id'] = $_SESSION['user'];
                                 if($details['group_id']!=null){
                                   $ans_group = array();
                                   foreach($group_det as $key=>$value){
$ans_group[] = $key;
                                   }
                                    $sql_vars['group_users'] = implode("','",$ans_group);
                                 }else{
                                    $sql_vars['group_users'] = $_SESSION['user'];
                                 }

                                 foreach ($sql_vars as $key => $value) {
                                 $regexp = "/{{{$key}}}/";
                                   $field['field_sql'] = preg_replace($regexp, $value, $field['field_sql']);
                               }
                        $select_s = $conn_data->query($field['field_sql'] . " {$demo} ");
                        $j = 0;
                        $last = 0;
                        while ($title = $select_s->fetch()) {
                          if($details_content[$field['field_name']]==$title['id']){



?>
                                    <label for="<?= $field['field_name'] ?>"
                                           class='d-block pb-2'><?= $field['field_title'] ?></label>

                                    <div class='input-group input-group-sm'>
                                        <input type="text"
                                               class="form-control"
                                               id='SHOW_<?=$field['field_name']?>'
                                               value='<?= $title['id'] ?> | <?= $title['title'] ?> | <?= $title['comment'] ?>'
                                               readonly="readonly">
                                        <a class="btn btn-outline-secondary px-2"
                                           href="#"
                                           data-toggle="modal"
                                           data-modal="2"
                                           data-id="<?=$details_content[$field['field_name']]?>"
                                           data-content="add-<?=$table_noid[0]?>"
                                           data-call="<?=$title['comment']?>"
                                           data-title="<?=$title_modal?>"
                                           data-bs-tooltip="tooltip"
                                           title="Otwórz szczegóły"><i class="ps-1 fas fa-external-link-alt"></i></a>
                                        <button class="btn btn-outline-secondary clipboard"
                                                type="button"
                                                data-clipboard-target="#<?=$field['field_name']?>"
                                                data-bs-tooltip="tooltip"
                                                title="Skopiuj do schowka"><i class="fas fa-clipboard"></i></button>
                                        </input>
                                    </div>
                                    <?php
                          }
                        }
                        ?>
                                </div>
                            </div>
                            <?php

                        ///////////////////////////////


















$sql_vars['user_id'] = $_SESSION['user'];

           foreach ($sql_vars as $key => $value) {
             $regexp = "/{{{$key}}}/";
             $field['field_sql'] = preg_replace($regexp, $value, $field['field_sql']);
         }
$sql_det = $conn_data->query($field['field_sql']);
if($sql_det->rowCount() > 0 ){
    $row_det = $sql_det-> fetch();

  
    ?>

                            <?php
}
                                }
                                                ?>

                        </div>
                        <?php
                                        break;
                                        case 'email':
                                        case 'decimal':
                                        case 'tel':
                                        case 'date':
                                        case 'time':
                                        case 'text':
                                        if ($details_content[$field['field_name']] != null) {
                                        ?>
                        <div class="col-<?= $field['row_size'] ?>  mb-1 d-flex flex-column">
                            <label for="<?= $field['field_name'] ?>"><?= $field['field_title'] ?></label>
                            <div class='input-group input-group-sm'>
                                <input type="text"
                                       class="form-control"
                                       id='SHOW_<?=$field['field_name']?>'
                                       value='<?= $details_content[$field['field_name']] ?>'
                                       readonly="readonly">
                                <button class="btn btn-outline-secondary clipboard"
                                        type="button"
                                        data-clipboard-target="#<?=$field['field_name']?>"
                                        data-bs-tooltip="tooltip"
                                        title="Skopiuj do schowka"><i class="fas fa-clipboard"></i></button>
                                </input>
                            </div>
                        </div>
                        <?php
                                        }
                                            ?>

                        <?php
                                            break;
                                        case 'radio':
                                        case 'checkbox':
                                            if ($details_content[$group['group_name']]!=null){
                                                if($field['field_sql']==null){

                                                
                                                if (strpos($details_content[$group['group_name']], $field['field_name']) !== false) {
                                                ?>
                        <div class='checkbox'>
                            <span class='badge bg-primary fs-6 m-1'><?= $field['field_title'] ?></span>
                        </div>
                        <?php
                                                }
                                            }else{

                                                $sql_vars['user_id'] = $_SESSION['user'];
                                                $sql_vars['preference_id'] = 3;
                    
                                                           foreach ($sql_vars as $key => $value) {
                                                             $regexp = "/{{{$key}}}/";
                                                             $field['field_sql'] = preg_replace($regexp, $value, $field['field_sql']);
                                                         }

                                                $pref = $conn_data->query($field['field_sql']);
                                                $pref_row = $pref -> fetch();
                                               $ans_sql = explode(" | ",$details_content[$group['group_name']]);
                                               foreach($ans_sql as $w){
                                                if (strpos($w, $pref_row['title']) !== false) {
                                                    ?>

                        <div class='checkbox'>
                            <span class='badge bg-primary fs-6 m-1'><?= $w ?></span>
                        </div>
                        <?php
                                                }
                                               }
                                                
                                            }
                                            }

                                            ?>

                        <?php
                                            break;
                                    }
                                }
                                ?>

                    </div>
                    <?php

                                    }
                                        ?>
                </div>
                <?php


                        }

                            ?>
            </div>
        </div>
    </div>