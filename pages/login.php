<?php
    if(isset($_POST['submit']))
    {
        if(isset($_POST['inputEtuPersoPass']) and !empty($_POST['inputEtuPersoPass']) and isset($_POST['inputPassword']) and !empty($_POST['inputPassword']))
        {
            $inputEtuPersoPass = $_POST['inputEtuPersoPass'];
            $inputPassword = $_POST['inputPassword'];
            //!\\//!\\//!\\//!\\//!\\ POUR LE DEV //!\\//!\\//!\\//!\\//!\\
            //$inputEtuPersoPass = "21999997";
            //$inputPassword = "durand";
            //!\\//!\\//!\\//!\\//!\\//!\\//!\\//!\\//!\\//!\\

            // Si c'est pas un ID LDAP alors on test avec un id de la base mysql
            // @ -> Car sinon un warning apparait si l'utilisateur existe pas.
            if(@connectionLdap($inputEtuPersoPass,$inputPassword) or login($inputEtuPersoPass, $inputPassword)) {
                $_SESSION['success'] = "Vous êtes maintenant connecté!";
                session_write_close();
                header('location: '.WEBROOT.'accueil');
            } else {
                $_SESSION['error'] = "Les identifiants sont incorects  !";
            }
        } else {
            $_SESSION['error'] = "Une erreur est apparu. Veuillez réessayer !";
        }
    } else {
        if(isset($_SESSION['Auth'])){
            $_SESSION['error'] = "Vous êtes déja connecté!";
            session_write_close();
            header('location: '.WEBROOT.'accueil');
        }
    }
?>
<form method="POST" class="form-signin" style="max-width: 330px; padding: 15px; margin: 0 auto;">
    <h2 class="form-signin-heading">Connectez-vous</h2>
    <h5>Avec votre persopass, etupass ou avec un identifiant spéficique</h5>
    <label for="inputEtuPersoPass" class="sr-only">Persopass ou Etupass</label>
    <input name="inputEtuPersoPass" class="form-control" placeholder="Identifiant" autofocus="" type="text">
    <label for="inputPassword" class="sr-only">Mot de passe</label>
    <input name="inputPassword" class="form-control" placeholder="Mot de passe"  type="password">
    <button name="submit" class="btn btn-lg btn-primary btn-block" type="submit">Se connecter</button>
</form>