<?php
    if(!isset($_SESSION['Auth'])){
        $_SESSION['error'] = "Vous n êtes pas connecté!";
        session_write_close();
        header('location: '.WEBROOT.'login');
    }
?>

Votre identifiant est le : <?php echo $_SESSION['Auth']['user'] ?> <br>
Vous vous appeler : <?php echo $_SESSION['Auth']['givenname'] ?> <br>
Votre adresse mail est : <?php echo $_SESSION['Auth']['email'] ?> <br>
<?php var_dump($_SESSION['Auth']['elempedag']) ?> <br>
<?php echo $_SESSION['Auth']['diplome'] ?> <br><br>
<a type="button" class="btn btn-info" href="<?php echo WEBROOT ?>signout">Se déconnecter</a>
