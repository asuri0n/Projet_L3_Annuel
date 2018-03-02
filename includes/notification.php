<?php
if(isset($_SESSION['error']) and !empty($_SESSION['error']))
{
    echo '<script>toast("'.$_SESSION['error'].'", "error", "Erreur", 5000)</script>';
    unset($_SESSION['error']);
}
if(isset($_SESSION['success']) and !empty($_SESSION['success']))
{
    echo '<script>toast("'.$_SESSION['success'].'", "success", "SuccÃ¨s", 5000)</script>';
    unset($_SESSION['success']);
}
