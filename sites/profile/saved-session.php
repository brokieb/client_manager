<div class="content" data-group="0">     <div class="alert alert-info" role="alert">
Znajdują się tutaj przeglądarki w których wybrałeś opcję zapamiętania przy logowaniu, jeżeli nie rozpoznajesz którejś z sesji, możesz wylogować konto z tego urządzenia klikając w krzyżyk po prawej stronie
</div>
    </div>

<?php
            $saved = $conn_user->query("SELECT `save_id`,`device_name` FROM `saved_device` WHERE `user_id` = {$_SESSION['user']} ");
            $saved->execute();
            if ($saved->rowCount() > 0) {
            ?>
                <ul class="list-group">
                    <?php
                    while ($saved_data = $saved->fetch()) {
                    ?>

                        <li class="list-group-item list-group-item-action d-flex justify-content-between"><?= $saved_data['device_name'] ?><button type='button' class='btn btn-outline remove-saved-device' data-id='<?= $saved_data['save_id'] ?>'><i class="fas fa-times"></i></button></li>

                    <?php
                    }
                    ?>
                </ul>
            <?php
            }
            ?>
            