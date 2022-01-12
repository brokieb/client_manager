<table class='table table-stripped table-bordered'>
    <thead>
        <tr>
            <th>ID</th>
            <th>Imię i nazwisko</th>
            <th>Telefon</th>
            <th>Notatki</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>

 

<?php

$select = $conn_data->query("
SELECT 'Nieruchomość' as 'table',`build_id` as 'id', CONCAT(`build_client-name`,' ' ,`build_client-surname`) as 'name', `build_client-phone` as 'phone',`row_status`,`build_comment` as 'comment' FROM `build` WHERE `user_id`={$_SESSION['user']} AND (`row_status`=1 OR `row_status`=2) UNION 
SELECT 'Klient' as 'table',`client_id` as 'id', CONCAT(`client_client-name`,' ',`client_client-surname`) as 'name', `client_client-phone` as 'phone',`row_status`,`client_comment` as 'comment' FROM `client` WHERE `user_id`={$_SESSION['user']} AND (`row_status`=1 OR `row_status`=2) ");
$select->execute();
if($select->rowCount()>0){
    while($row = $select->fetch()){
?>
<tr>
    <td>#<?=$row['id']?> - <?=$row['table']?></td>
    <td><?=$row['name']?></td>
    <td><?=$row['phone']?></td>
    <td><?=$row['comment']?></td>
    <td>
        <?php
        switch($row['row_status']){
            case '1':
                ?>
UDANE
                <?php
                break;
            case '2':
                ?>
NIEUDANE
                <?php
                break;
        }
?>
</td>
</tr>
<?php
    }
}
?>
   </tbody>
</table>