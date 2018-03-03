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

    $status = (isset($_SESSION['Auth']['isTeacher'])) ? "professeur" : ((isset($_SESSION['Auth']['isAdmin'])) ? "admin" : ((isset($_SESSION['Auth']['isStudent'])) ? "étudiant" : false));

    if($status){
        $content = "";
        $content .= "<h1>Mon espace $status </h1>";

        // Pour étudiants
        if(isset($_SESSION['Auth']['isStudent'])) {
            $content .= "Votre identifiant est le : " . $_SESSION['Auth']['user'] . " <br>";
            $content .= "Votre adresse mail est : " . $_SESSION['Auth']['email'] . " <br>";
            $content .= "Vous vous appeler : " . $_SESSION['Auth']['givenname'] . " <br><br>";
            foreach ($_SESSION['Auth']['elempedag'] as $elem) {
                if (is_numeric($elem))
                    $content .= "Mes $elem cours : <br>";
                else
                    $content .= $elem . "<br>";
            }
            $content .= "<br> Mon cursur : " . $_SESSION['Auth']['diplome'] . "<br><br>";
            $content .= "<a type=\"button\" class=\"btn btn-info\" href=\"<?php echo WEBROOT ?>signout\">Se déconnecter</a>";
        } else if(isset($_SESSION['Auth']['isAdmin'])) {
            ob_start();
            include_once 'admin.php';
            $content .= ob_get_contents();
            ob_end_clean();
        } else if(isset($_SESSION['Auth']['isTeacher'])) {

        } else {
            $content .= "Erreur, veuillez contacter l'administrateur";
        }
    }

    echo $content;