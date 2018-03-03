<?php
    if(isset($params[1])) {
        if (isset($_SESSION['Auth'])) {
            if ($params[1] == "admin" && !isset($_SESSION['Auth']['isAdmin'])) {
                $_SESSION['error'] = "Vous n'avez pas acces a cette page admin!";
            } else if ($params[1] == "teacher" && !isset($_SESSION['Auth']['isTeacher'])) {
                $_SESSION['error'] = "Vous n'avez pas acces a cette page professeur!";
            } else if ($params[1] == "student" && !isset($_SESSION['Auth']['isStudent'])) {
                $_SESSION['error'] = "Vous n'avez pas acces a cette page étudiante!";
            }
        } else {
            $_SESSION['error'] = "Vous n'êtes pas connecté!";
        }
    } else {
        $_SESSION['error'] = "Vous n'êtes pas acces a cette page!";
    }

    if(isset($_SESSION['error'])){
        session_write_close();
        header('location: '.WEBROOT.'accueil');
    }
?>

Votre identifiant est le : <?php echo $_SESSION['Auth']['user'] ?> <br>
Vous vous appeler : <?php echo $_SESSION['Auth']['givenname'] ?> <br>
Votre adresse mail est : <?php echo $_SESSION['Auth']['email'] ?> <br>
<?php var_dump($_SESSION['Auth']['elempedag']) ?> <br>
<?php echo $_SESSION['Auth']['diplome'] ?> <br><br>
<a type="button" class="btn btn-info" href="<?php echo WEBROOT ?>signout">Se déconnecter</a>
