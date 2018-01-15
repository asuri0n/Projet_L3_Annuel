<?php
    if(!isset($_SESSION['Auth'])){
        $_SESSION['error'] = "Vous n'êtes pas connecté!";
        session_write_close();
        header('location: '.WEBROOT.'login');
    }

    unset($_SESSION['Auth']);
    $_SESSION['success'] = "Vous êtes déconnecté!";
    session_write_close();
    header('location: '.WEBROOT.'accueil');