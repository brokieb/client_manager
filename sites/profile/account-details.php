            <ul id='account-details'>
                <li>ID użytkownika <strong><?= $details['uid'] ?></strong></li>
                <li>Login użytkownika <strong><?= $details['username'] ?></strong></li>
                <li>Email użytkownika <strong><?= $details['email'] ?></strong></li>
                <li>Konto utworzono <strong><?= $details['created'] ?></strong></li>
                <li>Licznik zalogowań <strong><?= $details['login_count'] ?></strong></li>
                <li>Błędnych logowań <strong><?= $details['login_fails'] ?></strong></li>
                <li>Ostatni błąd logowania <strong><?= $details['last_fail'] ?></strong></li>
                <?php
                if ($details['group_id'] == null) {
                ?>
                    <li>Nie należysz do żadnej grupy, brak zaproszeń</li>
                <?php
                } else {
                }             ?>
                <?php
                $google = new GoogleCalendarApi;
                if ($details['google_api'] != NULL) {
                    $data = $google->GetRefreshedAccessToken($details['google_api']);
                    $access_token = $data['access_token'];
                    $user_google = $google->GetCalendarsList($access_token);
                }
                if (isset($user_google)) {
                    if (is_array($user_google)) {
                ?>
                        <li>Połączenie z kontem google Calendar <strong><?= $user_google[0]['id'] ?></strong>
                        </li>
                <?php
                    }
                }