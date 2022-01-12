<?php
if (isset($_GET['back'])) {
    $back = "back=" . $_GET['back'] . "&content=" . $_GET['content'];
} else {
    $back = "";
}
?>
<div class='d-flex justify-content-around flex-wrap'>
    <form id="login-form" class="form col-12 col-md-5 col-xl-3" action="handle/login_handle.php?<?= $back ?>" method="post">

        <?php
        if (isset($_GET['back'])) {
        ?>
            <div class="alert alert-info m-2" role="alert">
                Żeby zobaczyć szczegóły tej pozycji, musisz się najpierw zalogować
            </div>
        <?php
        }
        ?>



        <div class="mb-3 form-floating">
            <input type='hidden' name='mode' value='0'>
            <input class="form-control" id="THE_username" name="THE_username" minlength="3" maxlength="24" placeholder="Login" type="text">
            <label class="form-label" for="THE_username">Login:</label>
        </div>

        <div class="mb-3 form-floating">
            <input class="form-control" type="password" minlength="<?php echo configAndConnect::MIN_PASSWORD_LENGTH; ?>" maxlength="<?php echo configAndConnect::MAX_PASSWORD_LENGTH; ?>" aria-label="password" name="THE_password" id="THE_password" placeholder="Hasło" required>
            <label class="form-label" for="username">Hasło:</label>
        </div>
        <div id="register-link" class="d-inline-flex justify-content-between w-100">
            <div class='d-flex align-items-center'>

            
            <button type="submit" class="btn btn-primary me-4">Zaloguj się</button>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name='THE_remember' value="true" id="flexCheckDefault">
                <label class="form-check-label" for="flexCheckDefault">
                    Zapamiętaj
                </label>
            </div>
            </div>
            <div class='d-flex flex-column text-end'>
                <!-- <a href="?site=register" class="my-1">Załóż konto</a> -->
                <a href="?site=pwreset" class="my-1">Odzyskaj hasło</a>
            </div>

        </div>
        <?php
        if(isset($_COOKIE['remember'])){
            $insert = $conn_user->prepare('SELECT `users`.`uid`,`users`.`username` FROM `saved_device` INNER JOIN `users` ON `saved_device`.`user_id`=`users`.`uid` WHERE `cookie_value`=?');
            $insert->execute([$_COOKIE['remember']]);
            if($insert->rowCount()>0){
                $user = $insert->fetch();
                ?>
<div>
            <h3 class='mt-4 mb-3'>Zapamiętane konta</h3>
            <a href='handle/login_handle.php?remember=1' class="row g-0 bg-primary position-relative p-3">
                <div class='d-flex align-items-center'>
                    <i class="fas fa-2x fa-user-tie me-3"></i>
                    <h5 class="m-0"><?=$user['username']?></h5>
                    
                </div>
            </a>
        </div>
                <?php
            }
        }
        ?>
        
    </form>

    <div class='col-lg-5'>
        <h3>Aktualności</h3>
        Najnowsza aktualizacja : <strong>03-09-2021</strong>, szczegóły oraz co się zmieniło możesz sprawdzić <a href="index.php?site=changelog">TUTAJ</a>
        <?php
        // include('sites/changelog.php');
        ?>
    </div>
</div>