    <div class='row m-auto p-2'>
        <span class='col-12 col-sm-6 d-flex justify-content-center align-items-center'>
            <button class="btn btn-secondary m-2"
                    data-bs-toggle="collapse"
                    href="#adv-search"
                    role="button"
                    aria-expanded="false"
                    aria-controls="adv-search"><i class="fas fa-search px-2"></i>Wyszukiwanie zaawansowane</button>
        </span>
        <?php
if($_GET['site']=='build-list'&&$details['group_id']){
    ?>
 <form method='POST'  action="index.php?site=<?=$_GET['site']?>" class='col-12 col-sm-6 row m-auto d-flex align-items-end'>
            <div  class='col-12 col-xl-9'>
                <label for='x'>Pozycje innego agenta</label>
                <select class='select js-states form-control' name='ids[]' multiple="multiple"
                        style="width:100%;">
                        <?php
foreach($group_det as $key=>$value){
    if(isset($_POST['ids'])){
    if(in_array($key,$_POST['ids'])){
?>
<option selected="selected" value="<?=$key?>"><?=$value?></option>
<?php
    }else{ 
        ?>
        <option value="<?=$key?>"><?=$value?></option>
        <?php
    }  
  }else{
        ?>
        <option value="<?=$key?>"><?=$value?></option>
        <?php
    }
    ?>

    <?php
}
                        ?>
                </select>
</div>
            <input type='hidden' name='group_data' value='1'>
            <button type='submit' class='btn btn-success col-12 col-xl-3 '>Wczytaj</button>
</form>
    <?php
}
        if (isset($_POST['00mode'])) {
        ?>
        <div class="col-12 col-sm-6 d-flex justify-content-center align-items-center">
        <a  href='?site=<?= $_GET['site'] ?>'
           class='btn btn-warning  m-2 '>Usuń aktywne filtry</a>
        </div>
        <?php
        } elseif (isset($_GET['backTo']) || isset($_GET['cView'])) {
        ?>
        <div class="alert alert-primary my-2"
             role="alert">
            Aktualnie przeglądasz niestandardowy wynik bazy, jeżeli chcesz przeglądać wszystkie wyniki <a
               href='?site=<?= $_POST['site'] ?>'>Kliknij tutaj</a>, lub przejdź ponownie na tą podstronę klikając w
            odpowiednią ikonę po lewej stronie w menu
        </div>

        <?php
        }
        ?>
    </div>
    <?php
    $site = 'add-'.$config['config']['site'];
    
    include('components/adv-search.php');
    if(isset($config['config']['legend'])){
        ?>
    <div class='p-2'>
        <h4>Legenda </h4>
        <?php
        foreach ($config['config']['legend'] as $key => $value){
            ?>

        <span class='badge p-2 m-1 bg-<?=$key?>'><?=$value?></span>
        <?php
        }
        ?>
    </div>
    <?php
    }
    ?>

    <table class='table table-bordered col-lg-9'
           data-columns='<?=json_encode($config)?>'>
        <thead>

            <tr>
                <?php
                foreach ($config['columns'] as $key=>$value) {
                ?>
                <th class='col-<?=$value?>'><?= $key ?></th>
                <?php
                }
                ?>
            </tr>
        </thead>
        <tbody>

            <?php
            $i = 1;
            $pagination = 11;
            $c_pagination = 0;
            $offset = 0;

            if(isset($config['config']['order'])){
                $order = $config['config']['order'];
            }else{
                $order = $config['config']['site']."_id DESC";
            }
         if(!isset($c_order)){
             $c_order = "0";
         }


         if(isset($config['config']['group_rows'])){
            foreach($config['config']['group_rows'] as $key=>$value){
                $contents[] = "SELECT `{$config['config']['site']}_id` as 'id' FROM `{$config['config']['site']}` LEFT JOIN `{$key}` ON `{$config['config']['site']}`.`{$value}`=`{$key}`.`{$key}_id` WHERE `{$key}`.`user_id` = {$_SESSION['user']} ";
            }
            $my_ids = implode(" UNION ",$contents);
            $ans_ids = $conn_data->query($my_ids);
            if($ans_ids->rowCount()>0){
               $group_content_ids = $ans_ids->fetchAll(PDO::FETCH_COLUMN);
               $group_ids_ans = "OR `{$config['config']['site']}_id` IN ('".implode("','",$group_content_ids)."') ";
            }


         }else{
             $group_ids_ans = NULL;
         }
            if (isset($_POST['00mode'])) {
                $filter = "";
                foreach ($_POST as $key => $value) {
                    if ($i == 0) {
                        $i++;
                    } else {
                        if ($value!=null) {
                            if (!is_array($value)) {


                                if (strpos($value, ",") !== false) {
                                    $find_array = explode(",", $value);
                                    foreach ($find_array as $z) {
                                        $filter .= " AND `" . $key . "` " . $z;
                                    }
                                } elseif (strpos($value, "<") !== false) {
                                    $filter .= " AND `" . $key . "` " . $value;
                                } elseif (strpos($value, ">") !== false) {
                                    $filter .= " AND `" . $key . "` " . $value;
                                } elseif (strpos($key, "00mode") === false) {
                                    $filter .= " AND `" . $key . "` LIKE '%" . $value . "%'";
                                }
                            } else {
                                foreach ($value as $x) {
                                    $filter .= " AND `" . $key . "` LIKE '%" . $x . "%'";
                                }
                            }
                        }
                    }
                }

                $select = $conn_data->query("SELECT *,{$c_order} as 'order_table' FROM `{$config['config']['site']}` WHERE `row_status` IS NULL AND `user_id`={$_SESSION['user']} {$demo} {$filter} ORDER BY {$order} LIMIT {$pagination} OFFSET {$offset} ");
            } elseif (isset($_GET['backTo'])) {

                $ans = explode("|", $_GET['backTo']);
                $offset = $ans[1] - 1;
                $sql = str_replace("OFFSET 0", "OFFSET " . ($offset), $_SESSION['q_pagination']);
            } elseif (isset($_GET['cView'])) {
               $select = $conn_data->query("SELECT *,{$c_order} as 'order_table' FROM `{$config['config']['site']}` WHERE `row_status` IS NULL AND `user_id`={$_SESSION['user']} {$demo} AND `{$config['config']['site']}_id`<={$_GET['cView']} ORDER BY {$order} LIMIT {$pagination} OFFSET {$offset} ");
            } elseif(isset($_POST['group_data'])){
                foreach($_POST['ids'] as $z){
if(!in_array($z,array_keys($group_det))){
    // echo "SPOZA LISTY!!";
    exit;
}
                }
                $ids = implode("','",$_POST['ids']);

                $select = $conn_data->query("SELECT *,{$c_order} as 'order_table' FROM `{$config['config']['site']}` WHERE `row_status` IS NULL AND `user_id` IN ('{$ids}') {$demo} ORDER BY {$order} LIMIT {$pagination} OFFSET {$offset} ");
            }else{
                $select = $conn_data->query("SELECT *,{$c_order} as 'order_table' FROM `{$config['config']['site']}` WHERE `row_status` IS NULL AND `user_id`={$_SESSION['user']} {$group_ids_ans} {$demo} ORDER BY {$order} LIMIT {$pagination} OFFSET {$offset} ");
            }

                        $_SESSION['q_pagination'] = $select->queryString;
            $count = 0;
            while ($row = $select->fetch()) {
                $count++;
                if ($c_pagination == $pagination) {
                    $c_pagination++;
                    break;
                } else {
                    $c_pagination++;
                }
                include('view/'.$config['config']['site'].'-list.php');
            }
            ?>


        </tbody>
        <?php
        if ($c_pagination >= $pagination) {
            $_SESSION['q_pagination'] = $select->queryString;
        ?>
        <tfoot>
            <tr>
                <td colspan='100'
                    class='p-0'>
                    <button class='btn btn-primary w-100 '
                            id='load-more'
                            data-view='<?=$config['config']['site']?>-list'
                            data-offset='<?= ($offset) ?>'>Doładuj więcej</button>
                </td>
            </tr>
        </tfoot>
        <?php
        }
        ?>

    </table>
    <?php
    if ($count == 0) {
    ?>
    <div class="alert alert-danger"
         role="alert">
        Brak elementów do wyświetlenia, najpierw coś dodaj ;)
    </div>
    <?php
    }

    ?>