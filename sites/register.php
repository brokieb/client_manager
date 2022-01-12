<form method="post" class="form col-lg-3 m-auto" action="handle/register_handle.php">
            <div class="mb-3 form-floating">
                <input class="form-control" id="THE_username" placeholder="Login" name="THE_username" minlength="<?=configAndConnect::MIN_USERNAME_LENGTH?>" maxlength="<?=configAndConnect::MAX_USERNAME_LENGTH?>" type="text">
                <label for="THE_username" class="form-label">twój login:</label>
            </div>

            <div class="mb-3 form-floating">
                <input type="email" minlength="6" maxlength="60" aria-label="email" class="form-control" name="THE_email" id="THE_email" aria-describedby="email" placeholder="Twój email" required>
                <label for="THE_email">Twój e-mail:</label>
            </div>
            <div class="mb-3 form-floating">
                <input type="password" minlength="<?=configAndConnect::MIN_PASSWORD_LENGTH?>" maxlength="<?=configAndConnect::MAX_PASSWORD_LENGTH?>" aria-label="Hasło" class="form-control" name="THE_password" id="THE_password" placeholder="Hasło" required>
                <label for="THE_password">Utwórz hasło:</label>
            </div>
            <div id="register-link" class="d-inline-flex justify-content-between w-100">
                <button type="submit" class="btn btn-primary">Załóż konto</button>
                <a href="?site=login" class="">Mam już konto</a>
            </div>
        </form>