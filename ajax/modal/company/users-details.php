<div class="alert alert-primary" role="alert">
        <h4 class="alert-heading">Zarządzanie pracownikami</h4>
        <p>
            W poniższym panelu wyświetlają sie pracownicy którzy są połączeni z twoją agencją. 
            
<p></p>
</div>
<?php
include('../../default.php');
$det = new accountDetails();
$details = $det->accountData();

$select = $conn_user->prepare("SELECT * FROM `users` WHERE `group_id` = ? AND `uid` <> ? AND `login_count`>0");
$select->execute([$details['group_id'],$details['uid']]);
if($select->rowCount()>0){
    ?>
    <div class="table-responsive">

   
<table class='table table-striped no-column table-bordered '>
    <thead>
        <tr>
            <th  class='text-nowrap'>ID</th>
            <th class='text-nowrap'>Email</th>
            <th class='text-nowrap'>Ostatnie logowanie</th>
            <th class='text-nowrap'>Logowań</th>
            <th class='text-nowrap'>Kal. google</th>
            <th class='text-nowrap'>Blokada?</th>
            
        </tr>
    </thead>
    <tbody>


    <?php
    while($row = $select->fetch()){
        ?>
<tr>
            <td class='text-nowrap'><?=$row['uid']?></td>
            <td class='text-nowrap'><?=$row['email']?></td>
            <td class='text-nowrap'><?=$row['last_login_at']?></td>
            <td class='text-nowrap'><?=$row['login_count']?></td>
            <td class='text-nowrap'>
                <?php
if($row['google_api']==NULL){
    ?>
NIE
    <?php
}else{
?>
TAK
<?php
}
                ?>
            </td>
            <td class='text-nowrap'>
                <?php
$ban = $conn_user->prepare("SELECT `locked_until` FROM `account_locks` WHERE `ip`= ?");
$ban->execute([$row['last_login_ip']]);
if($ban->rowCount()>0){
    $row_ban= $ban->fetch();
    ?>
<div class="form-check form-switch">
  <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" checked>
  <label class="form-check-label" for="flexSwitchCheckChecked">Blokada konta</label>
</div>
do <?=$row_ban['locked_until']?>
    <?php
}else{
    ?>
<div class="form-check form-switch">
  <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault">
  <label class="form-check-label" for="flexSwitchCheckDefault">Blokada konta</label>
</div>
    <?php
}
                ?>
            </td>
</tr>
        <?php
    }
    ?>
    </tbody>
</table>
</div>
    <?php
}else{
    ?>
Żadne konto nie jest jeszcze powiązane z twoim biurem
    <?php
}



$invited = $conn_user->prepare("SELECT 'false' as 'new',`email` as 'email',`uid` as 'id',`invite_create-date` as 'date' FROM `group_invite` INNER JOIN `users` ON `users`.`uid`=`group_invite`.`invite_user-id` WHERE `group_invite`.`group_id` = ? UNION SELECT 'true' as 'new',`email` as 'email',`uid` as 'id',`created` as 'date' FROM users WHERE `group_id` = ? AND `login_count` = 0");
$invited->execute([$details['group_id'],$details['group_id']]);
if($invited->rowCount()>0){
    ?>
<h2>Wysłane zaproszenia</h2>
<table class='table table-striped no-column table-bordered'>
    <thead>
        <tr>
            <th>ID</th>
            <th>Email</th>
            <th>Zaproszono</th>
        </tr>
    </thead>
    <tbody>

   
<?php
while($row = $invited->fetch()){
    ?>
<tr>
    <td><?=$row['id']?></td>
    <td>
        <?=$row['email']?>
<?php
if($row['new']=='true'){
   ?>
<span class="m-1 badge bg-success">Nowe konto</span>
   <?php
}else{
    ?>
sta
    <?php
}
?>
</td>
    <td><?=$row['date']?></td>
</tr>
    <?php
}
?>
 </tbody>
</table>
<?php
}
?>