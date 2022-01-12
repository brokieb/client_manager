<?php
include('../default.php');
if(!isset($_SESSION['back-to'])){
    ?>
    <div class="alert alert-info" role="alert">
Będą pojawiać się tutaj ostatnio przeglądane pozycje ze wszystkich dostępnych list. Stosowny komunikat o zapisaniu pozycji wyswietli się na ekranie podczas przewijania
</div>
    <?php
}else{

foreach ($_SESSION['back-to'] as $key => $value) {
?>
    <div class="form-check">
        <input class="form-check-input" type="radio" name="cView" id="<?= $value['id'] ?>" data-site="<?= $key ?>" value="<?= $value['id'] ?>">
        <label class="form-check-label" for="<?= $value['id'] ?>">
            <?php
        $table = explode("-", $key);
          $select = $conn_data->query("SELECT * FROM `{$table[0]}` WHERE `user_id`={$_SESSION['user']} {$demo} AND `{$table[0]}_id`={$value['id']} ");
          $select->execute(); 
          if($select->rowCount()>0){
            while($row = $select->fetch()){
                ?>
                <?= $value['title'] ?> | #<?= $row[$table[0] . '_id'] ?> <?= $row[$table[0] . '_client-name'] ?> <?= $row[$table[0] . '_client-surname'] ?>
           <?php
            }
          }

            ?>
            
        </label>
    </div>

    <input type='hidden' name='site' value='<?= $key ?>'>
<?php
}
}
?>