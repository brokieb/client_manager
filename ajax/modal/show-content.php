<?php
include('../default.php');
$site = explode("-",$_POST['content']);
$det = new accountDetails();
$details = $det->userGroupAllowed($_POST['id']);

if(is_array($details)){
    
    
    if(in_array($_SESSION['user'],$details)){
        $group = implode("','",$details);
    }else{
        $group = $_SESSION['user'];
    }
}else{
    $group = $_SESSION['user']; 
}
$select = $conn_data->prepare("SELECT * FROM `{$site[1]}` WHERE {$demo} `user_id` IN ('{$group}') AND `{$site[1]}_id`= ? ;");
$select->execute([$_POST['id']]);
$details = $select->fetch();
if($site[1]=='meet'){
    if($details['meet_sync']!=null){
        ?>
<span class="p-2 badge bg-success"><i class="far pe-2 fa-check-circle"></i>Spotkanie zsynchronizowane z Google Calendar</span>
        <?php
    }
}
if($_SESSION['user']!=$details['user_id']){
    $secured = " AND  `group_secured` = false ";
}else{
    $secured = null;
}



?>

<h3></h3>

<?php



        $select_g = $conn_form->query("SELECT * FROM `form-group` WHERE `group_form`='add-{$site[1]}' {$secured} ORDER BY CASE WHEN `group_type` = 'text' then 1 WHEN `group_type` = 'select' then 1 WHEN `group_type` = 'textarea' then 2 else 3 end ASC;");
        $select_g->execute();
        $i = 0;
        $last = 0;
        ?>

            <table class='table table-bordered table-sm table-striped no-column'>
        <?php
        while ($group = $select_g->fetch()) {
            if($group['group_name']==null){
                ?>
<tr>
    <th colspan='2' class='table-primary'><?=$group['group_title']?></th>
            </tr>
               <?php
               $select_f = $conn_form->prepare("SELECT * FROM `form-field` WHERE `group_id` = ? ORDER BY field_order ASC");
               $select_f->execute([$group['group_id']]);
               while($field = $select_f->fetch()){
                   ?>
<tr>
<th>as <?=$field['field_title']?></th>
<td><?=$details[$field['field_name']]?></td>
</tr>

<?php
               }
               ?>
                <?php
            }else{
                ?>
<tr>
    <th>ss <?=$group['group_title']?></th>
    <td><?=$details[$group['group_name']]?></td>
</tr>
                <?php
            }

                        }

                            ?>

</table>

*/