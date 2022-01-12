<?php
include('default.php');

if (stripos($_POST['source'], 'build') !== false) {
    $columns_sql=$conn_data->query("select * FROM `build` LIMIT 1");
    $columns_sql->execute();
    while ($columns = $columns_sql->fetch(PDO::FETCH_ASSOC)) {
        $ans = array_keys($columns);

    }
    if (stripos($_POST['source'], 'all') === false) {
        $order = "ORDER BY `{$_POST['source']}_id` DESC";
    }else{
        $order = null;
    }
    $sql[] = "SELECT CONCAT_WS('#',`build_id`) as 'id',
 ' ' as 'date',
        CONCAT(`build_client-name`,' ',`build_client-surname`) as 'title',
        `build_client-phone` as 'contact',
        'home' as 'type',
        'build' as 'table',
        `build_comment` as 'comment'
         FROM `build` WHERE `row_status` IS NULL AND `user_id`={$_SESSION['user']} {$demo} AND CONCAT_WS(`" . implode($ans, "`,`") . "`) LIKE :query {$order} ";
}

if (stripos($_POST['source'], 'client') !== false) {
    $columns_sql=$conn_data->query("select * FROM `client` LIMIT 1");
    $columns_sql->execute();
    while ($columns = $columns_sql->fetch(PDO::FETCH_ASSOC)) {
        $ans = array_keys($columns);
    }
    if (stripos($_POST['source'], 'all') === false) {
        $order = "ORDER BY `{$_POST['source']}_id`";
    }else{
        $order = null;
    }
    $sql[] = "SELECT CONCAT_WS('#',`client_id`) as 'id',
        ' ' as 'date',
        CONCAT(`client_client-name`,' ',`client_client-surname`) as 'title',
        `client_client-phone` as 'contact',
        'user' as 'type',
        'client' as 'table',
        `client_comment` as 'comment'
         FROM `client` WHERE `row_status` IS NULL AND `user_id`={$_SESSION['user']} {$demo} AND CONCAT_WS(`" . implode($ans, "`,`") . "`) LIKE :query {$order} ";
}

if (stripos($_POST['source'], 'meet') !== false) {
    $columns_sql=$conn_data->query("select * FROM `meet` LIMIT 1");
    $columns_sql->execute();
    while ($columns = $columns_sql->fetch(PDO::FETCH_ASSOC)) {
        $ans = array_keys($columns);
    }
    if (stripos($_POST['source'], 'all') === false) {
        $order = "ORDER BY `meet`.`meet_date` DESC, `meet`.`meet_time` ASC  ";
    }else{
        $order = null;
    }
    $sql[] = "SELECT 
        CONCAT_WS('#',`meet_id`) as 'id',
        CONCAT(`meet_date`,' ',`meet_time`) as 'date',
        `meet_client-name` as 'title',
        `meet_client-phone` as 'contact',
        'calendar' as 'type',
        'meet' as 'table',
        `meet_comment` as 'comment'
         FROM `meet` WHERE `row_status` IS NULL AND `user_id`={$_SESSION['user']} {$demo} AND CONCAT_WS(`" . implode($ans, "`,`") . "`) LIKE :query {$order} ";
}
if (stripos($_POST['source'], 'all') !== false) {

    $sql = implode($sql, " UNION ALL ");
} else {
    $sql = implode($sql, " ");
}
$select=$conn_data->prepare($sql);
$select->execute(array(":query" => "%". $_POST['query'] . "%"));
if ($select->rowCount() > 0) {
    while ($row = $select->fetch()) {

?>
<tr style='cursor: pointer;'>
    <td>
        <?php
switch($row['table']){
    case 'build':
        $title = "Szczegóły nieruchomości o ID #".$row['id'];
        break;
    case 'client':
        $title = "Szczegóły klienta o ID #".$row['id'];
        break;
    case 'meet':
        $title = "Szczegóły spotkania o ID #".$row['id'];
        break;
}

?>
            <a data-toggle="modal"
                data-modal="2"
                data-content="add-<?= $row['table'] ?>"
               data-title="<?= $title ?>"
               data-call="<?= $row['contact'] ?>"
               data-id="<?= ltrim($row['id'], '#') ?>">
                <table class='pb-4 m-0 w-100'>
                    <tr class='gap-1'>
                        <td class='col-12 col-lg-1 pe-2 text-nowrap'><i class="fas fa-<?= $row['type'] ?>"></i> #<?= $row['id'] ?></td>
                        <td class='col-12 col-lg-5'><?= $row['title'] ?></td>
                        <td class='col-12 col-lg-3'> <?= $row['date'] ?></td>
                        <td class='col-12 col-lg-3'> <?= $row['contact'] ?></td>
                    </tr>
                    <tr>
                        <td colspan='4'
                            class='pb-4 py-1'><i><?= $row['comment'] ?></i></td>
                    </tr>
                </table>
            </a>
            <a href='?site=<?= $row['table'] ?>-list&cView=<?= ltrim($row['id'], '#') ?>'>
            </a>
    </td>
</tr>
<?php
    }
} else {
    ?>
<tr style='cursor: pointer;'>
    <td>
        <div class="alert alert-danger"
             role="alert">
            Nie udało się nic znaleźć, spróbuj podać mniej wartości do wyszukania
        </div>
    </td>
</tr>
<?php
}
?>