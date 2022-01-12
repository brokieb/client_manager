<div class='d-grid w-100 '>
    <div class='row w-100 justify-content-around m-1'>


        <?php
        $preference = $conn_data->query("select * from `preference_user` where `preference_id`='{$config['config']['preference-id']}' AND `user_id`={$_SESSION['user']} ");
        if ($preference->rowCount() > 0) {
            $pref_row = $preference->fetch(PDO::FETCH_ASSOC);
            $pref_array = explode(" | ", $pref_row['preference_value']);
            $group = $conn_form->query("SELECT * FROM `form-group` WHERE `group_id` IN ( '" . implode("' , '", $pref_array) . "' ) ORDER BY `group_type` ASC");
            // $group = $conn_form->query("SELECT * FROM `form-group` WHERE `group_id`=9999");
            if ($group->rowCount() > 0) {
                while ($gr_row = $group->fetch(PDO::FETCH_ASSOC)) {
                    if ($gr_row['group_name'] == NULL) {

                        $field = $conn_form->query("SELECT * FROM `form-field` WHERE `group_id` = {$gr_row['group_id']} ");
                        if ($field->rowCount() > 0) {

                            $field_ans = array();
                            while ($field_fetch = $field->fetch()) {
                                if ($row[$field_fetch['field_name']] != NULL) {
                                    $field_ans[] = $field_fetch;
                                }
                            }
                            if (!empty($field_ans)) {
                                foreach ($field_ans as $x) { ?>





        <div class=" m-3 p-0 bg-secondary col-11 col-sm-5 col-lg-3 string-parent"
             role="alert"
             aria-live="assertive"
             aria-atomic="true">
            <div class="toast-header bg-primary">
                <strong class="me-auto text-white string-title fs-6"><?= $x['field_title'] ?></strong>
            </div>
            <?php
                                        if ($x['field_unit'] != null) {
                                            $unit = str_replace("^2", "<sup>2</sup>", $x['field_unit']);
                                        ?>
            <div class="toast-body text-dark py-2 px-4 string-value fs-6"><?= $row[$x['field_name']] ?> <?= $unit ?>
            </div>
            <?php
                                        } else {
                                        ?>
            <div class="toast-body text-dark py-2 px-4 string-value fs-6"><?= $row[$x['field_name']] ?></div>
            <?php
                                        }
                                        ?>


        </div>

        <?php
                                }
                            }
                        }
                    } else {
                        if ($row[$gr_row['group_name']] != NULL) {
                            ?>

        <div class="m-3 p-0 bg-secondary col-11 col-sm-5 col-lg-3 string-parent"
             role="alert"
             aria-live="assertive"
             aria-atomic="true">
            <div class="toast-header bg-primary py-2">
                <strong class="me-auto text-white string-title fs-6"><?= $gr_row['group_title'] ?></strong>
            </div>
            <div class="toast-body text-dark py-2 px-4 string-value fs-6">
                <?= str_replace([" | ", "-"], [", ", " "], $row[$gr_row['group_name']]) ?></div>
        </div>
        <?php
                        }
                    }
                }
            }
        }
        ?>
    </div>
</div>