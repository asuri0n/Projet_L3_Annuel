<?php
/**
 * Created by PhpStorm.
 * User: Nathan
 * Date: 08/03/2018
 * Time: 12:49
 */

if(!isset($_POST['deleteExercice']) or !is_numeric($_POST['inputIdExercice']))
    header('location: accueil');


$deleteExercice = newSQLQuery("DELETE FROM exercice WHERE id_exercice = ".$_POST['inputIdExercice'], "delete");

if($deleteExercice != null)
    if($deleteExercice)
        $_SESSION['success'] = "Exercice supprimé";
    else
        $_SESSION['error'] = "Une erreur s'est produite";
else
    $_SESSION['error'] = "Une erreur s'est produite";

