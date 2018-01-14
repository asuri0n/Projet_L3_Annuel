<?php
    if(isset($_POST['submit']))
    {
        if(isset($_POST['inputEmail']) and !empty($_POST['inputEmail']) and isset($_POST['inputPassword']) and !empty($_POST['inputPassword']))
        {
            login($_POST['inputPassword'], $_POST['inputPassword']);
        } else {
            $error = "Une erreur est apparu. Veuillez réessayer !";
            session_write_close();
            $_SESSION['error'] = $error;
        }
    }
?>
<form method="POST" class="form-signin" style="max-width: 330px; padding: 15px; margin: 0 auto;">
    <h2 class="form-signin-heading">Connectez-vous</h2>
    <label for="inputEmail" class="sr-only">Adresse mail</label>
    <input name="inputEmail" class="form-control" placeholder="Adresse mail"  autofocus="" type="email">
    <label for="inputPassword" class="sr-only">Mot de passe</label>
    <input name="inputPassword" class="form-control" placeholder="Mot de passe"  type="password">
    <div class="checkbox">
        <label>
            <input name="remember" type="checkbox"> Connection automatique
        </label>
    </div>
    <button name="submit" class="btn btn-lg btn-primary btn-block" type="submit">Se connecter</button>
</form>