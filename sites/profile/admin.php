<?php
    if ($_SESSION['user'] == 1) {
    ?>
    <div>
                <table class='no-column nowrap data-table table table-sm table-bordered'>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>LOGIN</th>
                            <th>EMAIL</th>
                            <th>OST. BŁ. LOGOWANIE</th>
                            <th>OST. LOGOWANIE</th>
                            <th><i class=" fas fa-users"></i></th>
                            <th><i class="fas fa-home"></i></th>
                            <th><i class="fas fa-calendar-alt"></i></th>
                            <th><i class="fas fa-history"></i></th>
                            <th><i class="fas fa-sign-in-alt text-danger"></i></th>
                            <th><i class="fas fa-envelope"></i></th>
                            <th><i class="fas fa-crown"></i></th>
                            <th><i class="fab fa-google"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $select = $conn_user->query("SELECT * FROM `users`");
                        $select->execute();
                        while ($row = $select->fetch()) {
                        ?>
                            <tr>
                                <td><?= $row['uid'] ?></td>
                                <td><?= $row['username'] ?></td>
                                <td><?= $row['email'] ?></td>
                                <td><?= $row['last_login_at'] ?></td>
                                <td><?= $row['last_fail'] ?></td>
                                <td>
                                    <?php
                                    $select_build = $conn_data->query("SELECT user_id,COUNT(user_id) as 'build' from `build` WHERE `user_id`={$row['uid']} group by `user_id`  ");
                                    $select_build->execute();
                                    if ($select_build->rowCount() > 0) {
                                        $build = $select_build->fetch();
                                        echo $build['build'];
                                    } else {
                                        echo 0;
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    $select_client = $conn_data->query("SELECT user_id,COUNT(user_id) as 'client' from `client` WHERE `user_id`={$row['uid']} group by `user_id`  ");
                                    $select_client->execute();
                                    if ($select_client->rowCount() > 0) {
                                        $client = $select_client->fetch();
                                        echo $client['client'];
                                    } else {
                                        echo 0;
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    $select_meet = $conn_data->query("SELECT user_id,COUNT(user_id) as 'meet' from `meet` WHERE `user_id`={$row['uid']} group by `user_id`  ");
                                    $select_meet->execute();
                                    if ($select_meet->rowCount() > 0) {
                                        $meet = $select_meet->fetch();
                                        echo $meet['meet'];
                                    } else {
                                        echo 0;
                                    }
                                    ?>
                                </td>
                                <td><?= $row['login_count'] ?></td>
                                <td><?= $row['login_fails'] ?></td>
                                <td><?= $row['activated'] ?></td>
                                <td><?= $row['privilege'] ?></td>
                                <td>
                                    <?php
                                    if ($row['google_calendar-default'] != NULL) {
                                        echo "OK!";
                                    }
                                    ?>
                                </td>
                            </tr>

                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            <?php
            if (isset($_POST['mode'])) {


                if ($_POST['mode'] == 'h') {
                    $_SESSION['user'] = $_POST['uid'];
                }
            }
            ?>
            <form method='post' action='index.php?site=profile/admin'>
                <label for='uid'>uid</label>
                <input type='password' name='uid' />
                <input type='hidden' name='mode' value='h' />
                <button type='submit' class='btn btn-sm btn-success'>1</button>
            </form>
            <hr/>
            <?php
$sql = $conn_data->query("SELECT * FROM errors");
while($row = $sql->fetch()){
    ?>
<ul>
    <li>TIME: <?=$row['err_time']?></li>
    <li>ID: <?=$row['err_id']?></li>
    <li>DATA: 
        <pre>
        <?php
        print_r(json_decode($row['err_json']));
        ?>
        </pre></li>
</ul>
    <?php
}
            ?>
        </div>

    <?php
    }

    ?> 