<?php
    // Vérification si l'exercice a bien été selectionné
    if(!isset($params[1]) or empty($params[1]) or !is_numeric($params[1]))
    {
        $_SESSION['error'] = "Veuillez d'abord cliquer sur un exercice";
        session_write_close();
        header('location: '.WEBROOT.'accueil');
    };

    // Récupère l'ID de l'exercice
    $exercice_id = $params[1];

    // Si il n'est pas connecté, on verifie si le niveau de l'exercice est L1
    if(!isset($_SESSION['Auth']))
    {
        $query = "SELECT a.num_annee as annee FROM exercice JOIN matieres m ON exercice.id_matiere = m.id_matiere JOIN annees a ON m.code_annee = a.code_annee WHERE id_exercice = ? LIMIT 1";
        $sem_id = getArrayFrom($pdo, $query, "fetch", "FETCH_NUM", $exercice_id)[0];

        if($sem_id != 1) {
            $_SESSION['error'] = "Vous devez vous connecter pour acceder a cet exercice";
            session_write_close();
            header('location: ' . WEBROOT . 'login');
        }
    }

    /*
     * Vérifications a chaque nouvelle affichage d'une question
     */
    $end = false;
    if(isset($_POST['next'])) // Si ce n'est pas la première question (Si il a cliqué sur Suivant)
    {
        // Vérification si toutes les données cachées sont passées
        if(isset($_POST['answers']) and isset($_POST['qnumber']) and isset($_POST['starttime']) and !empty($_POST['answers']) and !empty($_POST['qnumber']) and !empty($_POST['starttime']))
        {
            $nbQuestions = $_POST['qnumber'];
            $answers = str_split($_POST['answers']);
            $starttime = $_POST['starttime'];

            // Si une réponse a été cochée
            if(isset($_POST['quizz']) and is_numeric($_POST['quizz']))
            {
                // On rajoute la réponse dans la chaine de réponse
                foreach ($answers as $key => $answer) {
                    if($answer == '-') { // Dès qu'on trouve le premier caractère 'vide'
                        $answers[$key] = $_POST['quizz'];
                        break;
                    }
                }
            } else
                $_SESSION['error'] = "Pas de réponse selectionnée";
            
            // Si la chaine de caractère ne contient pas le meme nombre de caractères que de questions : erreur
            if(sizeof($answers) == $nbQuestions) {
                $cpt = 0;
                // Calcul des réponses
                foreach ($answers as $answer) {
                    if($answer != '-')
                        $cpt++;
                }
                // Si la taille des réponses et équivalent aux nombres de questions => Exercice terminée
                if($cpt == $nbQuestions)
                    $end = true;
                else // Sinon, on passe a la prochaine question
                    $nextQuestion = $cpt+1;
            } else {
                $_SESSION['error'] = "Erreur nombre de questions et nombres de réponses";
            }
        } else {
            $_SESSION['error'] = "Erreur données en POST";
        }
    } else {
        // Si c'est la première question. Initialisation du système
        $nbQuestions = getArrayFrom($pdo, "SELECT count(*) FROM questions WHERE id_exercice = ?", "fetch", "FETCH_NUM", $exercice_id)[0];
        $nextQuestion = 1;
        $answers = "";
        for($i = 0; $i < $nbQuestions; $i++)
            $answers .= "-";
        $answers = str_split($answers);
    }

    $content = "";
    // Si l'exercice n'est pas terminé
    if(!$end) {
        $question = getArrayFrom($pdo, "SELECT question, choix FROM questions JOIN type_question USING (id_type) WHERE id_exercice = ? ORDER BY id_question ASC LIMIT " . ($nextQuestion - 1) . ", 1 ", "fetch", "FETCH_ASSOC", $exercice_id);
        // Si la question comporte bien des choix de réponse
        if ($question["choix"]) {
            $exercice = getArrayFrom($pdo, "SELECT enonce FROM exercice WHERE id_exercice = ? LIMIT 1", "fetch", "FETCH_ASSOC", $exercice_id);
            $choix = explode(",", $question["choix"]);

            $content = "<h1>" . htmlentities($exercice['enonce']) . "</h1>";
            $content .= "<p style='margin-bottom:30px;'>$nextQuestion. " . htmlentities($question['question']) . "</p>";
            $content .= "<form role='form' name='quizform' action='" . WEBROOT . "exercices/" . $exercice_id . "' method='post'>";
            $content .= "<input name='starttime' value='1/15/2018 3:09:04 AM' type='hidden'>";
            $content .= "<input name='answers' value='" . implode($answers) . "' type='hidden'>";
            $content .= "<input name='qnumber' value='$nbQuestions' type='hidden'>";
            foreach ($choix as $key => $choi) {
                $content .= "<div class='radio'><label><input name='quizz' value='$key' type='radio'>" . htmlentities($choi) . "</label></div>";
            }
            $content .= "<br><input name='next' value=' Suivant ' type='submit'>";
            $content .= "</form>";
            $content .= "<div>Total $nbQuestions questions</div>";
        } else {
            $_SESSION['error'] = "Il n'y a pas de choix de réponse pour cette question. Veuillez contacter un administrateur.";
            session_write_close();
            header('location: ' . WEBROOT . 'accueil');
        }
    } else {
        // Affichage de fin
        $nbBonnesReponses = nbBonnesReponses($exercice_id, implode($answers));
        $percent = ($nbBonnesReponses*100/$nbQuestions);

        $content .= "<center><h2>Resultat:</h2>$nbBonnesReponses sur $nbQuestions<p><b>".$percent."%</b></p><p>".getSentenceResult($percent)."</p><p><b>Temps passé</b><br>0:27</p></center>";
        $content .= "<form role='form' target='_blank' action='".WEBROOT."resultat' method='post'>";
            $content .= "<input name='points' value='$nbBonnesReponses' type='hidden'>";
            $content .= "<input name='id' value='$exercice_id' type='hidden'>";
            $content .= "<input name='timespent' value='0:27' type='hidden'>";
            $content .= "<input name='answers' value='".implode($answers)."' type='hidden'>";
            $content .= "<table width='100%'><tbody><tr>";
                $content .= "<td><input value='Vérifier les réponses' name='checkAnswers' type='submit'></td>";
                $content .= "<td align='right'><input value='Réessayer' onclick='window.location.href = window.location.href;' type='button'></td>";
            $content .= "</tr></tbody></table>";
        $content .= "</form>";
    }

echo $content;