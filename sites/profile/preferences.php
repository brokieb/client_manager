            <h3>Kolejność bocznego menu</h3>
            <p class="card-text">Przesuwając poszczególne elementy możesz ustalić kolejność danych funkcji na pasku
                bocznym. Pierwszy element będzie tym podstawowym który będzie uruchamiał się odrazu po zalogowaniu -
                weź to pod uwagę :)</p>
            <form method='POST'
                  action='index.php?site=profile'>
                <div id="sortable"
                     class="list-group col-lg-4 log-12">
                    <?php
                    $select_order = $conn_data->query("select * from `preference_user` where `preference_id`=1 AND `user_id`={$_SESSION['user']} ");

                    if ($select_order->rowCount() > 0) {
                        $row_order = $select_order->fetch();
                        $custom_order = "ORDER BY ";
                        $custom_order .= "case nav_id";
                        $i = 1; 
                        $arr_order = explode(" | ", $row_order['preference_value']);
                        foreach ($arr_order as $b) {
                            $custom_order .= " when '" . $b . "' then " . $i . " ";
                            $i++;
                        }
                        $custom_order .= "end";
                    } else {
                        $custom_order = "ORDER BY `nav_id` ";
                    }
                    $select = $conn_form->query("SELECT * FROM `nav` WHERE `nav_privilege`<=1 AND (`nav_show_force`=1 OR `nav_show_force` IS NULL) AND `nav_show`=1 AND `nav_position`=1 {$custom_order} ");
                    if ($select->rowCount() > 0) {
                        while ($row = $select->fetch()) {
                    ?>
                    <a href="#"
                       class="ui-state-default list-group-item list-group-item-action"><i
                           class="fas pe-1 fa-grip-vertical"></i>
                        <i class="pe-1 fas <?= $row['nav_icon'] ?>"></i><?= $row['nav_title'] ?>
                        <input type='hidden'
                               name='pref-value[]'
                               value='<?= $row['nav_id'] ?>'>
                    </a>
                    <?php
                        }
                    }
                    ?>

                </div>
                <input type='hidden'
                       name='000mode'
                       value='preference-1'>
                <button type='submit'
                        class='btn btn-primary m-2'>Zapisz</button>
            </form>


            <hr class='mt-3'>
            <form method='POST'
                    action='index.php?site=profile/preferences'
                    id='preference-form'>
            <h3>Twoje regiony</h3>
            <?php
$ans = ['łąćż'];
echo implode(" | ",$ans);
            ?>
            <p class="card-text">Ważnym jest żeby uzupełnić regiony w których pracujemy, dzięki temu przy dodawaniu
                klientów/nieruchomości możemy określić lokalizację nieruchomości lub regiony w których klient szuka
                nieruchomości </br>
                Na dzień dzisiejszy nie ma możliwości żeby edytować lub usuwać istniejące juz regiony (można tylko
                dodawać nowe) . Jeżeli potrzebujesz usunąć region który jest już zapisany proszę o wiadomość na
                chacie.</br>
            </p>

            <div id="sortable"
                 class="list-group">
                <div class='col-lg-4 log-12'>
                    
                    <?php
                    $select = $conn_data->query("select * from `preference_user` where `preference_id`=3 AND `user_id`={$_SESSION['user']} ");
                          if ($select->rowCount() > 0) {
                        $row_order = $select->fetch();
                        $regions = explode(" | ",$row_order['preference_value']);
                        foreach($regions as $z){
                            ?>
    <div class="input-group py-1 px-2">
        <input readonly='readonly' type="text" name="pref-value[]" class="form-control " value='<?=$z?>'>
    </div>
                            <?php
                        }
                    }
                    

?>
<div class="input-group input-content py-1 px-2">
    <input type="text" name="pref-value[]" class="form-control required" placeholder='region' >
    <div class="invalid-feedback feedback"> </div>
    <div class="valid-feedback feedback"> </div>
</div>
<div class="input-group input-content copy-me py-1 px-2 d-none" >
    <input type="text" name="pref-value[]" class="form-control required" placeholder='region' >
    <button class="btn btn-outline-secondary remove-row" type="button"><i class="fas fa-times"></i></button>
    <div class="invalid-feedback feedback"> </div>
    <div class="valid-feedback feedback"> </div>
</div>
                        <input type='hidden'
                               name='000mode'
                               value='preference-3'>
                               <button type='button' id='add-new-row'
                                class='btn btn-primary m-2'>Dodaj kolejne pole</button>
                        <button type='submit'
                                class='btn btn-primary m-2'>Zapisz</button>
                    </form>
                </div>
                </div>

                <hr class='mt-3'>
                <h3>Wyświetalnie dodatkowych informacji</h3>
                <p class="card-text">
                    Możesz określić jakie szczegóły będą dodatkowo wyświetlane na liście z klientami i nieruchomościami.
                    Zaznaczenie odpowiednich kolumn przyśpieszy przeszukiwanie pozycji bez potrzeby wchodzenia w
                    szczegóły
                    każdej pozycji. W sytuacji w której dana pozycja nie posiada zaznaczonej przez Ciebie pozycji - nie
                    zostanie wyświetlona w podsumowaniu.
                </p>
                <?php
$ans = array(
    array(
        'title'=>'Szczegóły nieruchomości',
        'code'=>'add-build',
        'id'=>'5'
    ),
    array(
        'title'=>'Szczegóły klientów',
        'code'=>'add-client',
        'id'=>'6'
    )
);
foreach($ans as $z){
    ?>
                <form method='POST'
                      class='col-sm-6 p-3'
                      action='index.php?site=<?=$_GET['site']?>'>
                    <h4><?=$z['title']?></h4>
                    <?php
                $preference = $conn_data->query("select * from `preference_user` where `preference_id`={$z['id']} AND `user_id`={$_SESSION['user']} ");        
                if($preference->rowCount()>0){
                   $pref_row = $preference->fetch(PDO::FETCH_ASSOC);
                   $pref_array = $pref_row['preference_value'];
                } 
$select = $conn_form->query("SELECT * FROM `form-group` WHERE `group_form`='{$z['code']}' ");
$select->execute();
while($row = $select->fetch()){
    if($row['group_default']==1){
$disabled = "disabled checked";
    }else{
$disabled = null;
    }
    if(isset($pref_array)){
        if(strpos($pref_array,$row['group_id'])!==false){
            $checked = "checked";
        }else{
            $checked = null;
        }
    }else{
        $checked = null;
    }
    ?>
                    <div class="form-check ms-2">
                        <input class="form-check-input"
                               type="checkbox"
                               value="<?=$row['group_id']?>"
                               name="pref-value[]"
                               id="<?=$row['group_id']?>"
                               <?=$disabled?>
                               <?=$checked?>>
                        <label class="form-check-label"
                               for="<?=$row['group_id']?>">
                            <?=$row['group_title']?>
                        </label>
                    </div>

                    <?php
}
                ?>
                    <input type='hidden'
                           name='000mode'
                           value='preference-<?=$z['id']?>'>
                    <button type='submit'
                            class='btn btn-primary m-2'>Zapisz</button>
                </form>
                <?php
}
            ?>