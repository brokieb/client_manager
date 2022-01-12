            <table class="table no-column" id='modules'>
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nazwa</th>
                        <th scope="col">Przycisk</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope="row">1</th>
                        <td>Dane demonstracyjne</td>
                        <td>

                            <form method='POST' action='index.php?site=profile'>
                                <input type='hidden' name='000mode' value='preference-4'>
                                <?php
                                $select = $conn_data->query("select * from `preference_user` where `preference_id`=4 AND `user_id`={$_SESSION['user']} ");
                                $select->execute();
                                if ($select->rowCount() > 0) {
                                    $row_order = $select->fetch();

                                    if ($row_order['preference_value'] == 1) {
                                ?>
                                        <input type='hidden' name='pref-value' value='0'>
                                        <button class='btn btn-danger btn-sm'>WYŁĄCZ</button>
                                    <?php
                                    } else {
                                    ?>
                                        <input type='hidden' name='pref-value' value='1'>
                                        <button class='btn btn-success btn-sm'>WŁĄCZ</button>
                                    <?php
                                    }
                                } else {
                                    ?>
                                    <input type='hidden' name='pref-value' value='1'>
                                    <button class='btn btn-success btn-sm'>WŁĄCZ</button>
                                <?php
                                }
                                ?>

                            </form>

                        </td>
                    </tr>
                    <th scope="row">3</th>
                    <td>Sugestie</td>
                    <td><button class='btn btn-danger btn-sm' disabled='disabled'>WYŁĄCZ</button></td>
                    </tr>
                    <th scope="row">2</th>
                    <td>Wyłącz konto</td>
                    <td><button class='btn btn-danger btn-sm' disabled='disabled'>WYŁĄCZ</button></td>
                    </tr>

                </tbody>
            </table>

            <?php

if(isset($_COOKIE['alert'])){
    ?>
    <h2 class='mt-4'>Zablokowane komunikaty</h2>
<table class="table no-column">
    <thead>
        <tr>
            <td>ID</td>
            <td>USUŃ</td>
        </tr>
    </thead>
    <tbody>
        <?php
$arr = json_decode($_COOKIE['alert']);
foreach($arr as $z){
    ?>
<tr>
    <td><?=$z?></td>
    <td><button class='btn btn-sm btn-success show-alert' data-id='<?=$z?>'>POKAŻ PONOWNIE</button></td>
</tr>
    <?php
}

?>
    </tbody>
</table>
    <?php
}
?>