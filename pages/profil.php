<?php
    if(!isset($_SESSION['Auth'])){
        $_SESSION['error'] = "Vous n êtes pas connecté!";
        session_write_close();
        header('location: '.WEBROOT.'login');
    }
?>

Vous êtes connecté en tant que <?php echo $_SESSION['Auth']['email'] ?>
