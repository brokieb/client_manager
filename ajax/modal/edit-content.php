<?php
include('../default.php');
$site = explode("-", $_POST['content']);
$det = new accountDetails;
if($det->checkAllowed($site[1],$_POST['id'])){
$select = $conn_data->prepare(" SELECT * FROM `{$site[1]}` WHERE `user_id`={$_SESSION['user']} {$demo} AND `{$site[1]}_id`= ? ");
$select->execute([$_POST['id']]);
$edit = $select->fetch();
?>
<div class='d-flex justify-content-center flex-column'>
    <form method='POST'
          class='row justify-content-md-center'
          id='<?= $_GET['site'] ?>'>
        <input type='hidden'
               name='000mode'
               value='edit-<?= $site[1] ?>'>
        <input type='hidden'
               name='000id'
               value='<?= $_POST['id'] ?>'>
        <?php
        $select_g = $conn_form->query("SELECT * FROM `form-group` WHERE `group_form`='add-{$site[1]}' ORDER BY `group_id` ASC;");
        $select_g->execute();
        $index = 0;
        while ($group = $select_g->fetch()) {
        ?>

        <div class='col-12 border p-2 my-2'
             data-show-id="<?= $group['group_show'] ?>"
             data-actual>
            <div class='p-2 my-2'>
                <h2><?= $group['group_title'] ?></h2>
            </div>
            <?php
                $select_r = $conn_form->query("SELECT DISTINCT `group_id` FROM `form-field` WHERE `group_id`={$group['group_id']} ORDER BY `group_id` ;");
                $select_r->execute();
                while ($row = $select_r->fetch()) {
                    switch ($group['group_type']) {
                        case 'time': 
                            case 'date':
                            case 'decimal':
                            case 'tel':
                            case 'email':
                            case 'text':
                ?>
            <div class="row px-3">
                <?php
                            break;
                        case 'checkbox':

                            ?>
                <div class='d-flex flex-wrap justify-content-around'>
                    <?php
                                break;
                            case 'radio':
                                ?>
                    <div class="d-flex flex-column mx-auto col-xl-6 col-sm-12">
                        <?php
                                    break;
                                case 'textarea':
                                    ?>
                        <div class="d-flex">
                            <?php
                                            break;
                                    }
                                    $select_f = $conn_form->query("SELECT * FROM `form-field` WHERE `group_id`= {$row['group_id']} ORDER BY `field_order` ASC;");
                                    $select_f->execute();
                                    while ($field = $select_f->fetch()) {
                                        if ($field['field_name'] == null) {
                                            $field['field_name'] = str_replace(" ","-",strtolower($field['field_title']));
                                        }

                                        switch ($field['field_type']) {
                                            case 'time':
                                                case 'date':
                                                case 'text':
                                                case 'decimal':
                                                case 'email':
                                                case 'tel':
                                                if ($field['field_mask'] != NULL) {
                                                    $mask = "data-mask='" . $field['field_mask'] . "'";
                                                } else {
                                                    $mask = "";
                                                }
                                                if ($field['field_required'] == 1) {
                                                    $required = "required='required'";
                                    
                                                  } else {
                                                    $required = "";
                                                  }
                                            ?>
                            <div class="mb-3 px-1 col-12 col-sm-<?= $field['row_size'] ?>"
                                 data-show-id="<?=$field['field_show']?>">
                                <label class="text-nowrap"
                                       for="E_<?= $field['field_name'] ?>"><?= $field['field_title'] ?> </label>
                                <input <?=$required?>
                                       autocomplete="off"
                                       type="<?=$field['field_type']?>"
                                       class="form-control"
                                       id="E_<?= $field['field_name'] ?>"
                                       name='<?= $field['field_name'] ?>'
                                       placeholder="<?= $field['field_placeholder'] ?>"
                                       <?= $mask ?>
                                       value="<?= $edit[$field['field_name']] ?>"
                                       data-index="<?=$index?>">
                                <div id="invalid-E_<?= $field['field_name'] ?>"
                                     class="invalid-feedback"> </div>
                            </div>


                            <?php
                            $index++;
                                                break;
                                            case 'checkbox':
                                               

                                                if ($field['field_sql'] != NULL) {
                                                    $sql_vars['user_id'] = $_SESSION['user'];
                                                    $sql_vars['preference_id'] = 3;
                        
                                                               foreach ($sql_vars as $key => $value) {
                                                                 $regexp = "/{{{$key}}}/";
                                                                 $field['field_sql'] = preg_replace($regexp, $value, $field['field_sql']);
                                                             }
                                                    $select_check = $conn_data->query($field['field_sql']);
                                                    if ($select_check->rowCount() > 0) {
                                                        $checkbox_row = $select_check->fetch();
                                                        $arr = explode(" | ", $checkbox_row['title']);
                                                        foreach ($arr as $p) {
                                                            if (strpos($edit[$group['group_name']], $p) !== false) {
                                                                $checked = "checked='checked' ";
                                                            } else {
                                                                $checked = "";
                                                            }
                
                                                ?>
                            <div class='checkbox'>
                                <input class='btn-check'
                                       type="checkbox"
                                       name='<?= $group['group_name'] ?>[]'
                                       value='<?= $p ?>'
                                       id="E_<?= $p ?>"
                                       <?=$checked?>
                                       autocomplete="off">
                                <label class="btn btn-outline-secondary my-3 mx-2"
                                       for="E_<?= $p ?>"><?= $p ?></label>
                            </div>
                            <?php
                                                        }
                                                    } else {
                                                        ?>
                            <div class="alert alert-info"
                                 role="alert">
                                Możesz zdefiniować domyślne dane wyświetlane tutaj w zakładce profil na swoim koncie
                            </div>
                            <?php
                                                    }
                                                } else {
                                                    if (strpos($edit[$group['group_name']], $field['field_name']) !== false) {
                                                        $checked = "checked='checked' ";
                                                    } else {
                                                        $checked = "";
                                                    }
                                                    ?>
                            <div class='checkbox'>
                                <input class='btn-check'
                                       type="checkbox"
                                       <?=$checked?>
                                       name='<?= $group['group_name'] ?>[]'
                                       value='<?= $field['field_name'] ?>'
                                       id="E_<?= $field['field_name'] ?>"
                                       autocomplete="off">
                                <label class="btn btn-outline-secondary my-3 mx-2"
                                       for="E_<?= $field['field_name'] ?>"><?= $field['field_title'] ?></label>
                            </div>
                            <?php
                                                }
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
                                                    if ($select_check->rowCount() > 0) {
                                                        $checkbox_row = $select_check->fetch();
                                                        $arr = explode(" | ", $checkbox_row['title']);
                                                        foreach ($arr as $p) {
                                                            if ($edit[$group['group_name']] == $p) {
                                                                $checked = "checked='checked' ";
                                                            } else {
                                                                $checked = "";
                                                            }
                                                    ?>
                            <input <?= $checked ?>
                                   class="btn-check toggle-show"
                                   class="btn-check"
                                   type="radio"
                                   name='<?= $group['group_name'] ?>'
                                   value='<?= $p ?>'
                                   id='E_<?= $p ?>'
                                   autocomplete="off"
                                   data-toggle-id="<?= $field['field_id'] ?>">
                            <label class="btn btn-outline-secondary my-3 mx-2"
                                   for="E_<?= $p ?>"
                                   data-bs-target="[data-show-id=<?= $field['field_id'] ?>]"
                                   aria-expanded="false"
                                   aria-controls="[data-show-id=<?= $field['field_id'] ?>]"><?= $p ?></label>
                            <?php
                                                        }
                                                    } else {
                                                        ?>
                            <div class="alert alert-info"
                                 role="alert">
                                Możesz zdefiniować domyślne dane wyświetlane tutaj w zakładce profil na swoim koncie
                            </div>
                            <?php
                                                    }
                                                } else {
                                                    if ($edit[$group['group_name']] == $field['field_name']) {
                                                        $checked = "checked='checked' ";
                                                    } else {
                                                        $checked = "";
                                                    }
                                                    ?>
                            <input <?= $checked ?>
                                   class="btn-check toggle-show"
                                   class="btn-check"
                                   type="radio"
                                   name='<?= $group['group_name'] ?>'
                                   value='<?= $field['field_name'] ?>'
                                   id='E_<?= $field['field_name'] ?>'
                                   autocomplete="off"
                                   data-toggle-id="<?= $field['field_id'] ?>">
                            <label class="btn btn-outline-secondary my-3 mx-2"
                                   for="E_<?= $field['field_name'] ?>"
                                   data-bs-target="[data-show-id=<?= $field['field_id'] ?>]"
                                   aria-expanded="false"
                                   aria-controls="[data-show-id=<?= $field['field_id'] ?>]"><?= $field['field_title'] ?></label>
                            <?php
                                                }
                                                ?>
                            <?php
                                                break;
                                            case 'textarea':
                                            ?>
                            <textarea class="form-control"
                                      id="E_<?= $field['field_name'] ?>"
                                      name="<?= $field['field_name'] ?>"
                                      rows="3"
                                      form='edit'><?= Date("Y-m-d H:i") ?>-> &#13;&#10;#<?= $edit[$field['field_name']] ?></textarea>
                            <?php

                                                break;
                                                case 'select':
                        
                                                    ?>

                            <div class="mb-3 select-content col-<?= $field['row_size'] ?>"
                                 data-show-id="<?=$field['field_show']?>">
                                <div class="mt-1 select-div"
                                     data-show-id="<?=$field['field_show']?>">
                                    <label for="<?= $field['field_name'] ?>"
                                           class='d-block pb-2'><?= $field['field_title'] ?></label>
                                    <select class="select"
                                            style='width:100%;'
                                            name='<?=$field['field_name']?>' disabled='true'>
                                        >
                                        <option disabled='disabled'><?=$field['field_placeholder']?></option>
                                        <?php
                      
                      
                                                             $sql_vars['user_id'] = $_SESSION['user'];
                                                             $sql_vars['preference_id'] = 3;
                                                             if($details['group_id']!=null){
                                                               $ans_group = array();
                                                               foreach($group_det as $key=>$value){
                      if($key!=$_SESSION['user']){
                      $ans_group[] = $key;
                      }
                                                               }
                                                                $sql_vars['group_users'] = implode("','",$ans_group);
                                                             }
                      
                                                             foreach ($sql_vars as $key => $value) {
                                                             $regexp = "/{{{$key}}}/";
                                                               $field['field_sql'] = preg_replace($regexp, $value, $field['field_sql']);
                                                           }
                                                    $select_s = $conn_data->query($field['field_sql'] . " {$demo} ");
                                                    $j = 0;
                                                    $last = 0;
                                                    while ($title = $select_s->fetch()) {
                                                      if(isset($title['group'])){
                                                        $group_title = $group_det[$title['group']];
                                                        if($last!=$title['group']){
                                                          if($j==0){
                                                            ?>
                                        <optgroup label="<?=$group_title?>">
                                            <?php
                                                             $j++;
                                                          }else{
                                                            ?>
                                        </optgroup>
                                        <optgroup label="<?=$group_title?>">
                                            <?php
                                                          }
                                                          
                                                         
                                                          $last = $title['group'];
                                                        }
                                                      }
                                                      echo $edit[$field['field_name']];
                                                      if($edit[$field['field_name']]==$title['id']){
?>
                                            <option value='<?= $title['id'] ?>' selected><?= $title['id'] ?> |
                                                <?= $title['title'] ?> |
                                                <?= $title['comment'] ?></option>
                                            <?php
                                                      }else{
                                                          ?>
                                            <option value='<?= $title['id'] ?>'><?= $title['id'] ?> |
                                                <?= $title['title'] ?> |
                                                <?= $title['comment'] ?></option>
                                            <?php

                                                      }
                                                    ?>

                                            <?php
                                                    }
                                                    if(isset($last)){
                      ?>
                                        </optgroup>
                                        <?php
                                                    }
                                                    ?>
                                    </select>
                                </div>
                            </div>
                            <?php
                      if($field['field_improved']!=NULL){
                        $arra = json_decode($field['field_improved'],TRUE);
                        ?>
                            <div class='ps-3'>

                                <?php
                                                            $a = 2;
                                                            foreach($arra as $value){
                                                              if(isset($value['field_if'])){
                                                                if($details[$value['field_if']]!=null){
                                                               
                                                                  ?>
                                <div class="form-check pt-2">
                                    <input class="form-check-input toggle-show"
                                           type="radio"
                                           value="mine"
                                           id="show-text-select-<?= $value['field_name'] ?>-<?=$a?>"
                                           name="000-text-option-<?= $field['field_name'] ?>"
                                           data-toggle-id='<?=$value['field_id']?>'
                                           <?php
                                                                          if($edit[$value['field_name']]!=null){
                                                                          ?>
                                           checked
                                           <?php
                                                                          } 
                                
                                ?>
                                disabled='disabled'>
                                    <label class="form-check-label"
                                           for="show-text-select-<?= $value['field_name'] ?>-<?=$a?>"
                                           data-bs-target="[data-show-id=<?=$value['field_id']?>]"
                                           aria-controls="[data-show-id=<?=$value['field_id']?>]">
                                        <?=$value['field_title']?>
                                    </label>
                                </div>
                                <?php 
                                                                }
                                                              }else{
                                                                ?>
                                <div class="form-check pt-2">
                                    <input class="form-check-input toggle-show"
                                           type="radio"
                                           value="mine"
                                           id="show-text-select-<?= $value['field_name'] ?>-<?=$a?>"
                                           name="000-text-option-<?= $field['field_name'] ?>"
                                           data-toggle-id='<?=$value['field_id']?>'
                                           <?php
                                                                        if($edit[$value['field_name']]!=null){
                                                                        ?>
                                           checked
                                           <?php
                                                                        } 
                              
                              ?>
                               disabled='disabled'>
                                    <label class="form-check-label"
                                           for="show-text-select-<?= $value['field_name'] ?>-<?=$a?>"
                                           data-bs-target="[data-show-id=<?=$value['field_id']?>]"
                                           aria-controls="[data-show-id=<?=$value['field_id']?>]">
                                        <?=$value['field_title']?>
                                    </label>
                                </div>
                                <?php 
                                                              }
                                                              
                      
                      $a++;
                        }
                      ?>
                            </div>
                            <div>
                            </div>
                            <?php
                                                  }
                                                    ///////////////////////////////
                                                      break;
                                        }
                                    }
                                    if ($group['group_type'] == 'checkbox') {
                                        $arr = explode(" | ", $edit[$group['group_name']]);
                                        foreach ($arr as $z) {
                                            if (strpos($z, "*") !== false) {
                                            ?>
                            <div class='checkbox'>
                                <input checked='checked'
                                       class='btn-check'
                                       type="checkbox"
                                       name='<?= $group['group_name'] ?>[]'
                                       value='<?= $z ?>'
                                       id="E_<?= $z ?>"
                                       autocomplete="off">
                                <label class="btn btn-outline-secondary my-3 mx-2"
                                       for="E_<?= $z ?>"><?= $z ?></label>
                            </div>
                            <?php
                                            }
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



    </form>
</div>
<?php

}else{
    include(__DIR__.'/../../view/show-deny.php');
  }
?>