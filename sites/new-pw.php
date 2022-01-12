<?php
if (isset($_GET['key'])) {
?>
    <form id="login-form" class="form col-lg-3" action="index.php" method="post">

        <div class="mb-3 form-floating">
    <input class="form-control" type="password" minlength="<?=configAndConnect::MIN_PASSWORD_LENGTH; ?>" maxlength="<?=configAndConnect::MAX_PASSWORD_LENGTH; ?>" aria-label="password" name="THE_password" id="THE_password" placeholder="Hasło" required>
    <label class="form-label" for="username">Nowe hasło:</label>
</div>
        <input type='hidden' name='key' value='<?= $_GET['key'] ?>'>
        <input type='hidden' name='000mode' value='resetpw-set'>
        <div id="register-link" class="d-inline-flex justify-content-between w-100">
            <button type="submit" class="btn btn-primary mb-3">Zmień hasło</button>
        </div>
    </form>
<?php

} else { //No key
    $cc = new configAndConnect();
    $cc->outputString("Nieprawidłowy link");
}

