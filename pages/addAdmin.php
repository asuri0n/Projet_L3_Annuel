<?php
/**
 * Created by PhpStorm.
 * User: Nathan
 * Date: 12/03/2018
 * Time: 17:09
 */

if(isset($_POST['email']) and !empty($_POST['email']) and isset($_POST['password']) and !empty($_POST['password']))
    if(signup())
        $_SESSION['success'] = "Administrateur ajouté";
    else
        $_SESSION['error'] = "Erreur";
else
    $_SESSION['error'] = "Information manquante";


?>

<div style="background-color: cornsilk; padding:20px">
    <form method="POST">
        <div class="form-group">
            <label for="email">Adresse mail:</label>
            <input type="email" class="form-control" name="email">
        </div>
        <div class="form-group">
            <label for="password">Mot de passe:</label>
            <input type="password" class="form-control" name="password">
        </div>
        <input type="submit" class="btn btn-default" name="addAdmin" value="Ajouter">
    </form>
</div>
