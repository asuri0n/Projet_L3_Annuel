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
    if($status)
    {
        $content = "";
        $titre = "<h1>Mon espace $status </h1>";

        // Pour étudiants
        if(isset($_SESSION['Auth']['isStudent']))
        {
            $tab1 = "";
            $tab2 = "";

            /*
             * TAB N°1
             */
            $tab1 .= "Votre identifiant est le : " . $_SESSION['Auth']['user'] . " <br>";
            $tab1 .= "Votre adresse mail est : " . $_SESSION['Auth']['email'] . " <br>";
            $tab1 .= "Vous vous appeler : " . $_SESSION['Auth']['givenname'] . " <br><br>";

            $pedagarray = $_SESSION['Auth']['elempedag'];
            $tab1 .= "<h4>Mes ".sizeof($pedagarray)." cours : </h4>";

            // Requete pour récuperer les UE (sans les EC)
            $query = newSQLQuery("SELECT concat(code_semestre,code_ue), libelle, code_ue, code_semestre FROM matieres WHERE code_ec is null;", "select", "fetchAll", "FETCH_NUM");
            foreach ($pedagarray as $elem)
            {
                foreach ($query as $item)
                {
                    if($elem == $item[0])
                    {
                        $tab1 .= "<b>".$elem . " : " . $item[1] . "</b><br>";
                        // Les EC de l'UE
                        $subquery = newSQLQuery("SELECT libelle FROM matieres WHERE code_ec is not null and code_ue = '$item[2]' and code_semestre = '$item[3]';", "select", "fetchAll", "FETCH_NUM");
                        foreach ($subquery as $subitem)
                            $tab1 .= "&mdash;&mdash;	$subitem[0]"."<br>";
                    }
                }
            }
            $tab1 .= "<br> Mon cursur : " . $_SESSION['Auth']['diplome'] . "<br><br>";

            /*
             * TAB N°2
             */
            $scores = newSQLQuery("SELECT id_exercice, enonce, resultat FROM scores JOIN exercice USING (id_exercice) WHERE id_etudiant = ?", "select","fetchAll", "FETCH_ASSOC", $_SESSION['Auth']['user']);
            foreach ($scores as $score)
                $tab2 .= "Exercice n°".$score["id_exercice"]." : ".$score["enonce"] . "&nbsp;&nbsp;&mdash;&mdash;&nbsp;&nbsp;Score : " . $score["resultat"]."<br>";

            include_once './vues/student.php';

        } else if(isset($_SESSION['Auth']['isAdmin'])) {
            include_once './vues/admin.php';
        } else if(isset($_SESSION['Auth']['isTeacher'])) {
            $content .= "<h4>L'espace réservé aux professeurs est en cours de développement</h4>";
        } else {
            $content .= "Erreur, veuillez contacter l'administrateur";
        }
    }
    $content .= "<br><a type='button' class='btn btn-info' href='".WEBROOT."signout'>Se déconnecter</a>";

    echo $content;