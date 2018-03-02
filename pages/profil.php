<?php
    if(!isset($_SESSION['Auth'])){
        $_SESSION['error'] = "Vous n êtes pas connecté!";
        session_write_close();
        header('location: '.WEBROOT.'login');
    }
?>

Votre identifiant est le : <?php echo $_SESSION['Auth']['user'] ?> <br>
Vous vous appeler : <?php echo $_SESSION['Auth']['person'] ?> <br>
Votre adresse mail est : <?php echo $_SESSION['Auth']['email'] ?> <br>
