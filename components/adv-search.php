<div class='my-3 collapse' id='adv-search'>
    <div class="alert alert-primary" role="alert">
        <h4 class="alert-heading">Zatrzymaj się na chwilę!</h4>
        <p>W wyszukiwarce rozpoznawane są trzy typy danych: tekst, liczba, zaznaczenie. Przy polu do wpisania liczby (np. rok, ilość) możemy podać "</br>
            1995 ,> 2006 " - co będzie oznaczać że szukamy zapisanych mieszkań których dany parametr roku jest większy od '95 i mniejszy od '06.</br>
            Przy zaznaczeniu któregoś z przycisków, zostaną wyszukane elementy w których zostały zaznaczone te pola.</br>
            Pola do wpisania tekstu (np. ulica, nazwisko) rozpoznają kawałki tekstu, możesz wpisać "ak" - zostaną wtedy wyszukani klienci o nazwisku "nowak" i "woźniak"</br>
            Proszę pamiętać że wyszukiwarka jest czuła na błędy i przy dodaniu jednego z argumentów błędnie, zostanie zwrócony pusty wynik</p>
    </div>
    <form method='POST'>
    <div class='row mx-2'>
  
        <input type='hidden' name='00mode' value='adv-search'>

            <?php
            $select_g = $conn_form->query("SELECT * FROM `form-group` WHERE `group_form`='{$site}' AND `group_type` NOT IN ('select') ORDER BY CASE WHEN `group_type` = 'text' then 1 WHEN `group_type` = 'textarea' then 2 else 3 end ASC;");
            $select_g->execute();
            $i = 0;
            $last = 0;
            while ($group = $select_g->fetch()) {
                if ($i == 0) {
                    ?>
<div class='col-lg-7 col-sm-12 py-1'>
                    <?php
                    $i++;
                }
                if ($last != $group['group_type'] && $last == 'textarea') {
                    ?>
</div><div class='col-lg-5  col-sm-12 py-1'>
                    <?php
                }
                $last = $group['group_type'];


            ?>

                <div class='w-100'>
                    <div class='p-1'>
                        <h4 class='m-0'><?= $group['group_title'] ?></h4>
                    </div>
                    <?php
                    $select_r = $conn_form->query("SELECT DISTINCT `group_id` FROM `form-field` WHERE `group_id`={$group['group_id']} ORDER BY `group_id` ;");
                    while ($row = $select_r->fetch()) {
                        switch ($group['group_type']) {
                            case 'text':
                    ?>
                                <div class="py-2 row">
                                <?php
                                break;
                            case 'checkbox':
                                ?>
                                    <div class='py-2 d-flex flex-wrap '>
                                    <?php
                                    break;
                                case 'radio':
                                    ?>
                                        <div class='py-2 d-flex flex-wrap '>
                                        <?php
                                        break;
                                    case 'textarea':
                                        ?>
                                            <div class="py-2 d-flex">
                                            <?php
                                            break;
                                    case 'select':
                                        break;
                                    }
                                    $select_f = $conn_form->query("SELECT * FROM `form-field` WHERE `group_id`={$row['group_id']} ;");
                                    while ($field = $select_f->fetch()) {
                                        if ($field['field_name'] == null) {
                                            $field['field_name'] = strtolower(str_replace(array(" ", "ł", "ó", "ż", "ź", "ś", "ć", "ę", "ą", "ń"), array("-", "l", "o", "z", "z", "s", "c", "e", "a", "n"), $field['field_title']));
                                        }

                                            ?>

                                            <?php
                                            switch ($field['field_type']) {
                                                case 'tel':
                                                case 'decimal':
                                                case 'date':
                                                case 'time':
                                                case 'email':
                                                case 'text':
                                                    if ($field['field_mask'] != NULL) {
                                                        $mask = "data-mask='" . $field['field_mask'] . "'";
                                                    } else {
                                                        $mask = "";
                                                    }
                                                switch($field['field_search']){
                                                    case 'text':
                                                        $placeholder = $field['field_placeholder'];
                                                        break;
                                                    case 'range':
                                                        $placeholder = "<".$field['field_placeholder'];
                                                        break;
                                                    default:
                                                    $placeholder = $field['field_search'];
                                                    break;
                                                }
                                            ?>
                                                    <div class="mb-1 col-<?= $field['row_size'] ?> form-floating" >
                                                        <input type="text" class="form-control mask" id="<?=$field['field_id']?><?= $field['field_name'] ?>" name='<?= $field['field_name'] ?>' placeholder="<?= $placeholder ?>">
                                                        <label for="<?=$field['field_id']?><?= $field['field_name'] ?>"><?= $field['field_title'] ?> (<i><?= $placeholder ?></i> )</label>
                                                    </div>
                                                <?php
                                                    break;
                                                case 'checkbox':
                                                    if($field['field_sql']!=NULL){

                                                        $sql_vars['user_id'] = $_SESSION['user'];
                                                        $sql_vars['preference_id'] = 3;
                            
                                                                   foreach ($sql_vars as $key => $value) {
                                                                     $regexp = "/{{{$key}}}/";
                                                                     $field['field_sql'] = preg_replace($regexp, $value, $field['field_sql']);
                                                                 }

                                                        $select_full = $conn_data->query($field['field_sql']);
                                                        $select_full->execute();
                                                        $checkbox_row = $select_full->fetch();
                                                        $arr = explode(" | ",$checkbox_row['title']);
                                                        if(is_array($checkbox_row)){
                                                          foreach($arr as $p){


                                                            ?>
                                                            <div class='checkbox'>
                                                              <input class='btn-check' type="checkbox" name='<?= $group['group_name'] ?>[]' value='<?= $p ?>' id="<?=$field['field_id']?><?= $p ?>" autocomplete="off">
                                                              <label class="btn btn-sm btn-outline-secondary my-1 mx-1" for="<?=$field['field_id']?><?= $p ?>"><?= $p ?></label>
                                                            </div>
                                                          <?php
                                                          }
                                                        }
                                                        
                                                    }else{

?>
 <div class='checkbox'>
                                                        <input class='btn-check' type="checkbox" name='<?= $group['group_name'] ?>[]' value='<?= $field['field_name'] ?>' id="<?= $field['field_name'] ?>" autocomplete="off">
                                                        <label class="btn btn-sm btn-outline-secondary my-1 mx-1" for="<?= $field['field_name'] ?>"><?= $field['field_title'] ?></label>
                                                    </div>
<?php

                                                    }

                                                ?>
                                                   
                                                <?php
                                                    break;
                                                case 'radio':
                                                    if ($field['field_sql'] != NULL) {

                            $sql_vars['user_id'] = $_SESSION['user'];
                            $sql_vars['preference_id'] = 3;

                                       foreach ($sql_vars as $key => $value) {
                                         $regexp = "/{{{$key}}}/";
                                         $field['field_sql'] = preg_replace($regexp, $value, $field['field_sql']);
                                     }
                                                        $select_check = $conn_data->query($field['field_sql']);
                                                        $select_check->execute();
                                                        if($select_check->rowCount() > 0 ){
                                                          $checkbox_row =$select_check->fetch();
                                                          $arr = explode(" | ", $checkbox_row['title']);
                                                          foreach ($arr as $p) {

                                                      ?>
                                                            <input  class="btn-check toggle-show" class="btn-check" type="radio" name='<?=  $group['group_name'] ?>[]' id='<?=$field['field_id']?><?= $p ?>' value='<?= $p ?>' autocomplete="off" data-toggle-id="<?= $p ?>">
                                                      <label class="btn btn-sm btn-outline-secondary my-1 mx-1" for="<?=$field['field_id']?><?= $p ?>" data-bs-target="[data-show-id=<?= $field['field_id'] ?>]" aria-expanded="false" aria-controls="[data-show-id=<?= $field['field_id'] ?>]"><?= $p ?></label>
                                                          <?php
                                                          }
                                                        }
                                                      } else {
?>

 <div class='checkbox'>
                                                        <input class="btn-check" type="checkbox" name='<?= $group['group_name'] ?>[]' id='<?= $field['field_name'] ?>' value='<?= $field['field_name'] ?>' autocomplete="off">
                                                        <label class="btn btn-sm btn-outline-secondary my-1 mx-1" for="<?= $field['field_name'] ?>" ><?= $field['field_title'] ?></label>
                                                    </div>

<?php
                                                      }

                                                ?>
                                                   
                                                <?php
                                                    break;
                                                case 'textarea':
                                                    
                                                ?>

                                                    <div class="mb-1 col-<?= $field['row_size'] ?> form-floating" >
                                                        <input type="text" class="form-control mask" id="<?= $field['field_name'] ?>" name='<?= $field['field_name'] ?>' <?= $mask ?> placeholder="<?= $field['field_placeholder'] ?>">
                                                        <label for="<?= $field['field_name'] ?>"><?= $field['field_title'] ?>( <?=$field['field_placeholder']?> )</label>
                                                    </div>
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
                                    <div class='row d-flex justify-content-center my-3'>

                  <button type='submit' class='btn btn-primary w-75 p-3 '><i class="fas fa-search px-2"></i>WYSZUKAJ</button>
</div>


    </form>
</div>