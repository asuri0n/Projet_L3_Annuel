<?php
    if(!isset($_SESSION['Auth'])){
        $_SESSION['error'] = "Vous n'êtes pas connecté!";
        session_write_close();
        header('location: login');
    }