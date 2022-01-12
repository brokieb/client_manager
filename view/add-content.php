<form method='POST'
        class=''
        id='<?= $_GET['site'] ?>'>
      <input type='hidden'
             name='000mode'
             value='<?= $_GET['site'] ?>'>
      <?php
    $select = $conn_form->prepare("SELECT * FROM `form-group` WHERE `group_form`=? ;");
    $select->execute([$_GET['site']]);
    $index = 0;
    while ($group = $select->fetch()) {

    ?>

      <div class='border p-2 my-2'
           data-group-id="<?=$group['group_id']?>"
           data-show-id="<?= $group['group_show'] ?>"
           data-actual>
          <div class='p-2 '>
              <h2><?= $group['group_title'] ?></h2>
          </div>
          <?php
        $select_g = $conn_form->prepare("SELECT DISTINCT `group_id` FROM `form-field` WHERE `group_id`=" . $group['group_id'] . " ORDER BY `group_id` ;");
        $select_g->execute([$group['group_id']]);
        while ($row = $select_g->fetch()) {
          switch ($group['group_type']) {
            case 'time': 
            case 'date':
            case 'decimal':
            case 'tel':
            case 'email':
            case 'text':
        ?>
          <div class="row px-1">
              <?php
              break;
            case 'checkbox':
              ?>
              <div class='d-flex flex-wrap justify-content-around px-1"'>
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
                  case 'select':
                    ?>
                          <div class="row">
                              <?php
                      break;
                  }
                  $select_f=$conn_form->prepare("SELECT * FROM `form-field` WHERE `group_id`=?  ORDER BY `field_order` ASC ;");
                  $select_f->execute([$row['group_id']]);
                  while ($field = $select_f->fetch()) {
                    if ($field['field_name'] == null) {
                      $field['field_name'] = strtolower(str_replace(" ", "-", $field['field_title']));
                    }
                      switch ($field['field_type']) {
                        case 'time':
                        case 'date':
                        case 'text':
                        case 'decimal':
                        case 'email':
                        case 'tel':
                          if ($field['field_mask'] != NULL) {
                            $mask = " data-mask='" . $field['field_mask'] . "' ";
                          } else {
                            $mask = "";
                          }
                          if ($field['field_required'] == 1) {
                            $required = "required='required'";
            
                          } else {
                            $required = "";
                          }
                          $reverse = "";
                          if ($field['field_type'] == "") {
                            $type = $group['group_type'];
                          } else {
                            $type = $field['field_type'];
                          }


                          //
                      ?>
                              <div class="mb-3 px-3 col-12 col-sm-<?= $field['row_size'] ?> input-content"
                                   data-show-id="<?=$field['field_show']?>">
                                  <label class="text-nowrap"
                                         for="<?= $field['field_name'] ?>"><?= $field['field_title'] ?></label>
                                  <input autocomplete="off"
                                         type="<?= $type ?>"
                                         inputmode="<?= $field['field_type'] ?>"
                                         placeholder="<?= $field['field_placeholder']?>"
                                         class="form-control"
                                         id="<?= $field['field_name'] ?>"
                                         name='<?= $field['field_name'] ?>'
                                         <?= $required ?>
                                         <?= $mask ?>
                                         <?= $reverse ?> 
                                         data-index="<?=$index?>"/>
                                  <div id="invalid-<?= $field['field_name'] ?>"
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
                            if($select_check->rowCount() > 0 ){
                              $checkbox_row =$select_check->fetch();
                              $arr = explode(" | ", $checkbox_row['title']);
                              foreach ($arr as $p) {
                          ?>
                              <div class='checkbox'>
                                  <input class='btn-check'
                                         type="checkbox"
                                         name='<?= $group['group_name'] ?>[]'
                                         value='<?= $p ?>'
                                         id="<?= $p ?>"
                                         autocomplete="off">
                                  <label class="btn btn-outline-secondary my-3 mx-2"
                                         for="<?= $p ?>"><?= $p ?></label>
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
                            ?>
                              <div class='checkbox'>
                                  <input class='btn-check'
                                         type="checkbox"
                                         name='<?= $group['group_name'] ?>[]'
                                         value='<?= $field['field_name'] ?>'
                                         id="<?= $field['field_name'] ?>"
                                         autocomplete="off">
                                  <label class="btn btn-outline-secondary my-3 mx-2"
                                         for="<?= $field['field_name'] ?>"><?= $field['field_title'] ?></label>
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
                            if($select_check->rowCount() > 0 ){
                              $checkbox_row =$select_check->fetch();
                              $arr = explode(" | ", $checkbox_row['title']);
                              foreach ($arr as $p) {
                          ?>
                              <input class="btn-check toggle-show"
                                     class="btn-check"
                                     type="radio"
                                     name='<?=  $group['group_name'] ?>'
                                     value='<?= $p ?>'
                                     id='<?= $p ?>'
                                     autocomplete="off"
                                     data-toggle-id="<?= $p ?>">
                              <label class="btn btn-outline-secondary my-3 mx-2"
                                     for="<?= $p ?>"
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
                            ?>
                              <input class="btn-check toggle-show"
                                     class="btn-check"
                                     type="radio"
                                     name='<?= $group['group_name'] ?>'
                                     value='<?= $field['field_name'] ?>'
                                     id='<?= $field['field_name'] ?>'
                                     autocomplete="off"
                                     data-toggle-id="<?= $field['field_id'] ?>">
                              <label class="btn btn-outline-secondary my-3 mx-2"
                                     for="<?= $field['field_name'] ?>"
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
                              <div class="mb-3 w-100 form-floating">
                                  <textarea autocomplete="off"
                                            class="form-control"
                                            style='height:200px'
                                            placeholder="Leave a comment here"
                                            id="<?= $field['field_name'] ?>"
                                            name="<?= $field['field_name'] ?>"
                                            form='<?= $group['group_form'] ?>'></textarea>
                                  <label for="<?= $field['field_name'] ?>"><?= $field['field_title'] ?></label>
                              </div>
                              <?php

                          break;
                        case 'select':
                        
                        ?>

                              <div class="mb-3 select-content col-<?= $field['row_size'] ?>"
                                   data-show-id="<?=$field['field_show']?>"
                                   data-show-one="<?=$field['field_only-one']?>">
                                  <div class="mt-1 select-div"
                                       data-show-id="<?=$field['field_show']?>">
                                      <label for="<?= $field['field_name'] ?>"
                                             class='d-block pb-2'><?= $field['field_title'] ?></label>
                                      <select class="select"
                                              style='width:100%;'
                                              name='<?=$field['field_name']?>'>
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
                                        echo $regexp = "/{{{$key}}}/";
                                         $field['field_sql'] = preg_replace($regexp, $value, $field['field_sql']);
                                     }
                                     echo $field['field_sql'] . " {$demo} ";
                              $select_s = $conn_data->query($field['field_sql'] . " {$demo} ");
                              $j = 0;
                              $last = 0;
                              while ($title = $select_s->fetch()) {

                                echo $field['field_sql'];
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
                              ?>
                                              <option value='<?= $title['id'] ?>'><?= $title['id'] ?> |
                                                  <?= $title['title'] ?> |
                                                  <?= $title['comment'] ?></option>
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
                                  <div id="invalid-only-one"
                                       class="invalid-only-one invalid-feedback"> </div>
                              </div>
                              <?php
////////////////////////////////////
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
                                                    if($a==2){
                                                    ?>
                                                       checked
                                                       <?php
                                                    } 
          
          ?>>
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
                                                  if($a==2){
                                                  ?>
                                                     checked
                                                     <?php
                                                  } 
        
        ?>>
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
                      ?>
                              <div class='checkbox-new'
                                   data-custom-button-id="<?= $group['group_id'] ?>">
                                  <button type="button"
                                          class="btn btn-outline-secondary my-3 mx-2 customValue"
                                          data-toggle="modal"
                                          data-modal="10"
                                          data-site="modal/add-checkbox"
                                          data-id="<?= $group['group_id'] ?>"
                                          data-content="custom-value"
                                          data-title="Dodanie niestandardowego pola">
                                      INNE POLE
                                  </button>
                              </div>
                              <?php
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









                      <div class='row d-flex justify-content-center my-3'>

                          <button type='submit'
                                  class='btn btn-primary w-75 p-3 '><i class="fas fa-plus px-2"></i>DODAJ</button>
                      </div>
  </form>