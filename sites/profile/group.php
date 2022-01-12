            <?php
            $select = $conn_user->prepare("SELECT * FROM `group` WHERE `group_id`= ?");
            $select->execute([$details['group_id']]);
            if ($select->rowCount() > 0) {
                $row = $select->fetch();
            ?>
            <ul>
                <li>Nazwa grupy: <strong><?= $row['group_name'] ?></strong></li>
                <li>Domena: <strong>@<?= $row['group_domain'] ?></strong></li>
                <?php
                    if ($row['group_admin'] == $_SESSION['user']) {
                    ?>
                <li>Rola: <strong>Administrator</strong></li>

                <?php
                        $sql = $conn_user->prepare("SELECT count(group_id) as 'users' FROM `users` WHERE `group_id`= ?");
                        $sql->execute([$row['group_id']]);
                        $users = $sql->fetch();
                        ?>
                <li>Połączonych kont <strong><?= $users['users'] - 1 ?></strong></li>
                <li>Najbliższy okres rozliczeniowy <strong><?= $row['group_active-to'] ?></strong></li>
                <div class='d-flex flex-wrap justify-content-around p-2'>
                    <button class='btn btn-sm btn-primary m-2'>WSZYSTKIE STATYSTYKI</button>
                    <!-- <button class='btn btn-sm btn-primary m-2'>PRZEDŁUŻ</button> -->
                    <button class='btn btn-sm btn-primary m-2'
                            data-toggle="modal"
                            data-modal="13"
                            data-title="Szczegóły pracowników w grupie">ZARZĄDZAJ PRACOWNIKAMI</button>
                    <button class='btn btn-sm btn-primary m-2'
                            data-toggle="modal"
                            data-modal='12'
                            data-title="Zarządzaj pracownikami w grupie">DODAJ PRACOWNIKÓW</button>

                </div>
                <div>
                    <form method='POST'
                          action='index.php?site=profile/preferences'
                          id='preference-form'>
                        <h3>Dodatkowe ustawienia</h3>
                        <h5>Domyślne regiony dla grupy</h5>

                        <p class="card-text">Ważnym jest żeby uzupełnić regiony w których pracujemy, dzięki temu przy
                            dodawaniu
                            klientów/nieruchomości możemy określić lokalizację nieruchomości lub regiony w których
                            klient szuka
                            nieruchomości </br>
                            Na dzień dzisiejszy nie ma możliwości żeby edytować lub usuwać istniejące juz regiony (można
                            tylko
                            dodawać nowe) . Jeżeli potrzebujesz usunąć region który jest już zapisany proszę o wiadomość
                            na
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
                                    <input readonly='readonly'
                                           type="text"
                                           name="pref-value[]"
                                           class="form-control "
                                           value='<?=$z?>'>
                                </div>
                                <?php
                        }
                    }
                    

?>
                                <div class="input-group input-content py-1 px-2">
                                    <input type="text"
                                           name="pref-value[]"
                                           class="form-control required"
                                           placeholder='region'>
                                    <div class="invalid-feedback feedback"> </div>
                                    <div class="valid-feedback feedback"> </div>
                                </div>
                                <div class="input-group input-content copy-me py-1 px-2 d-none">
                                    <input type="text"
                                           name="pref-value[]"
                                           class="form-control required"
                                           placeholder='region'>
                                    <button class="btn btn-outline-secondary remove-row"
                                            type="button"><i class="fas fa-times"></i></button>
                                    <div class="invalid-feedback feedback"> </div>
                                    <div class="valid-feedback feedback"> </div>
                                </div>
                                <input type='hidden'
                                       name='000mode'
                                       value='preference-3'>
                                <button type='button'
                                        id='add-new-row'
                                        class='btn btn-primary m-2'>Dodaj kolejne pole</button>
                                <button type='submit'
                                        class='btn btn-primary m-2'>Zapisz</button>
                    </form>

                    <hr>




                    <!-- <form method='post'>
                        <h3>Ustawienia prywatności</h3>
                        <div class="form-check">
                            <input class="form-check-input"
                                   type="radio"
                                   name="group_mode"
                                   value="1"
                                   id="g_mode_1">
                            <label class="form-check-label"
                                   for="g_mode_1">
                                Agenci mogą przeglądać wszystkie pozycje innych agentów (jak swoje) ze
                                wszystkimi danymi klienta
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input"
                                   type="radio"
                                   name="group_mode"
                                   id="g_mode_2"
                                   value="2"
                                   checked>
                            <label class="form-check-label"
                                   for="g_mode_2">
                                Agenci mogą przeglądać szczegóły nieruchomości tylko dla pozycji wstępnie pasujących do własnych pozycji
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input"
                                   type="radio"
                                   name="group_mode"
                                   id="g_mode_3"
                                   value="3"
                                   checked>
                            <label class="form-check-label"
                                   for="g_mode_3">
                               Agenci nie mogą przeglądać żadnych danych innych agentów
                            </label>
                        </div>
                    </form> -->
                </div>
                <?php
                    } else {
                    ?>
                <li>Rola: <strong>Agent</strong></li>
                <?php
                    }
                    ?>
            </ul>
            <?php
            } else {
?>
            <div>
                <?php
                $select = $conn_user -> prepare("SELECT * FROM `group_invite` INNER JOIN `group` ON `group`.`group_id`=`group_invite`.`group_id` WHERE `invite_user-id` = ?");
                $select->execute([$_SESSION['user']]);
                if($select->rowCount()>0){
                    ?>
                <div class="alert alert-info"
                     role="alert">
                    Na twoje konto zostało wysłane minimum jedno zaproszenie do grupy, zapoznaj się z nimi poniżej i
                    podejmij decyzję. </br>
                    Pamiętaj że możesz należeć tylko do jednej grupy, oraz po dołączeniu cała grupa będzie miała dostęp
                    do twoich nieruchomości, klientów i spotkań - jak również ty do pozycji pracowników z grupy (
                    zależnie od ustawień grupy ).</br>
                    Dołączenie do grupy wiąże się również z tym że administrator grupy będzie mógł w każdym momencie
                    przejąć kontrolę nad twoim kontem i zablokować Ci do niego dostęp.</br>
                </div>
                </br>
                <table class='table table-striped table-bordered'>
                    <thead>
                        <tr>
                            <th>Nazwa grupy</th>
                            <th>Data zaproszenia</th>
                            <th>Dołącz</th>
                            <th>Odrzuć</th>
                        </tr>
                    </thead>
                    <tbody>


                        <?php
while($row = $select->fetch()){
                ?>
                        <tr>
                            <td><?=$row['group_name']?></td>
                            <td><?=$row['invite_create-date']?></td>
                            <td><button class='btn btn-sm btn-success'
                                        data-toggle="modal"
                                        data-modal="15"
                                        data-title="Potwierdź działanie"
                                        data-id="<?=$row['invite_id']?>"
                                        data-content='success'>ZAAKCEPTUJ</button></td>
                            <td><button class='btn btn-sm btn-danger'>ODRZUĆ</button></td>
                        </tr>

                        <?php
                }
                ?>
                    </tbody>
                </table>
                <?php
                }else{
                    ?>
                nie należysz do żadnej grupy, poczekaj na zaproszenie lub załóż własną kontaktując się z administratorem
                na chacie
                <?php
                }
            ?>
            </div>
            <?php
            }
            ?>