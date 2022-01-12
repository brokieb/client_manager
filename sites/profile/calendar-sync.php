            <p>W celu synchronizacji twoich spotkań z kalendarzem Google, powiąż swoje konto CM z kontem Google poniższym przyciskiem</p>
            <?php
            if ($details['google_api']==NULL) {
                $google = new GoogleCalendarApi;
                $config = $google->getGoogleDetails();
                $login_url = "https://accounts.google.com/o/oauth2/v2/auth?scope=" . urlencode("https://www.googleapis.com/auth/calendar") . "&redirect_uri={$config["CLIENT_REDIRECT_URL"]}&response_type=code&client_id={$config['CLIENT_ID']}&access_type=offline&prompt=consent";
            ?>
                <a id='login-google' href='<?= $login_url ?>'><img src='img/google-login.png' alt='zaloguj z google' style='width:150px'></a>
                <div class="alert alert-danger" role="alert">
                    Uwaga! Aplikacja nie jest jeszcze zweryfikowana z Google, na ten moment podczas próby połączenia się z Google otrzymamy informacje że połaczenie jest niebezpieczne, czekając na weryfikację od Google można się połączyć klikając na stronie błędu: [Zaawansowane] -> Otwórz: cmanager.pl (niebezpieczne)

                </div>
                </li>
            <?php
            } else {
            ?>
                <form method='POST' class='d-inline-block m-2'>
                    <p class='m-0'>Wybierz na którym kalendarzu z Google Calendar zapisywać spotkania</p>
                    <?php
                    $capi = new GoogleCalendarApi();
                    $data = $capi->GetRefreshedAccessToken($details['google_api']);
                    $access_token = $data['access_token'];
                    $calendars = $capi->GetCalendarsList($access_token);
                    foreach ($calendars as $w) {
                        if ($details['google_calendar-default'] == $w['id']) {
                            $checked = "
                                checked='checked' ";
                        } else {
                            $checked = "";
                        }
                    ?>
                        <div class="form-check">
                            <input <?= $checked ?>class="form-check-input" type="radio" name="calendar_id" value='<?= $w['id'] ?>' id="<?= $w['id'] ?>">
                            <label class="form-check-label" for="<?= $w['id'] ?>">
                                <?= $w['summary'] ?>
                            </label>
                        </div>
                    <?php
                    }
                    ?>
                    <input type='hidden' name='000mode' value='google-set_default'>
                    <button type='submit' class='btn btn-sm btn-success mx-1'>USTAW KALENDARZ</button>
                </form>
                <form method='POST' class='d-inline-block m-2'>
                    <input type='hidden' name='000mode' value='google-sync'>
                    <p>Kliknięcie tej opcji wczyta wszystkie przyszłe spotkania do kalendarzu Google</p>
                    <button type='submit' class='btn btn-sm btn-primary mx-1'>SYNCHRONIZUJ WSZYSTKIE SPOTKANIA</button>
                </form>
                <form method='POST' class='d-inline-block m-2'>
                    <input type='hidden' name='000mode' value='google-removeMeets'>
                    <p>Po kliknięciu wszystkie zsynchronizowane spotkania zostaną usuniętę z kalendarza google - spotkania w ClientManager NIE ZOSTANĄ USUNIĘTE</p>
                    <button type='submit' class='btn btn-sm btn-danger mx-1'>USUŃ SPOTKANIA Z KALENDARZA</button>
                </form>
                <form method='POST' class='d-inline-block m-2'>
                    <input type='hidden' name='000mode' value='google-delete'>
                    <p>Po kliknięciu zostanie usunięte połączenie z CM, już więcej spotkania nie będą ładowane do kalendarza Google</p>
                    <button type='submit' class='btn btn-sm btn-danger mx-1'>USUŃ POWIĄZANIE</button>
                </form>
               
            <?php
            }
            ?>