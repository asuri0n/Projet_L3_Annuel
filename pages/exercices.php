<?php
    // Vérification si l'exercice a bien été selectionné
    if(!isset($params[1]) or empty($params[1]) or !is_numeric($params[1])){
        $_SESSION['error'] = "Veuillez d'abord cliquer sur un exercice";
        session_write_close();
        header('location: '.WEBROOT.'accueil');
    };

    // Récupère l'ID de l'exercice
    $exercice_id = $params[1];

    if(!isset($_SESSION['Auth'])){
        $sem_id = getArrayFrom($pdo, "SELECT matieres.sem_id FROM exercice JOIN matieres USING (id_matiere) WHERE id_exercice = ".$exercice_id." LIMIT 1", "fetch")['sem_id'];

        if($sem_id > 2) {
            $_SESSION['error'] = "Vous devez vous connecter pour acceder a cet exercice";
            session_write_close();
            header('location: ' . WEBROOT . 'login');
        }
    }


    //
    // vérifications a chaque nouvelle affichage d'une question
    //
    $end = false;
    if(isset($_POST['next'])){
        if(isset($_POST['answers']) and isset($_POST['qnumber']) and !empty($_POST['answers'] and !empty($_POST['qnumber']))){
            $nbQuestions = $_POST['qnumber'];
            $answers = str_split($_POST['answers']);
            if(isset($_POST['quizz']) and is_numeric($_POST['quizz'])){
                // On rajoute la réponse dans la chaine
                foreach ($answers as $key => $answer) {
                    if($answer == '-') {
                        $answers[$key] = $_POST['quizz'];
                        break;
                    }
                }

            } else {
                echo "pas de réponse selectionnée";
            }

            if(sizeof($answers) == $nbQuestions) {
                $cpt = 0;
                foreach ($answers as $answer) {
                    if($answer != '-')
                        $cpt++;
                }
                if($cpt == $nbQuestions)
                    $end = true;
                else
                    $nextQuestion = $cpt+1;
            } else {
                echo "Erreur nombre de questions et nombres de réponses";
            }
        } else {
            echo "error answers and qnumber values";
        }
    } else {
        $nbQuestions = getArrayFrom($pdo, "SELECT count(*) FROM questions WHERE id_exercice = ".$exercice_id, "fetch", "FETCH_NUM")[0];
        $nextQuestion = 1;
        $answers = "";
        for($i = 0; $i < $nbQuestions; $i++)
            $answers .= "-";
        $answers = str_split($answers);
    }

    if(!$end)
        $question = getArrayFrom($pdo, "SELECT id_question, question, type_question.libelle as typelib, choix, reponses FROM questions JOIN type_question USING (id_type) WHERE id_exercice = ".$exercice_id." ORDER BY id_question ASC LIMIT ".($nextQuestion-1).", 1 ", "fetch");

    ob_start();
    if(!$end) {
        $exercice = getArrayFrom($pdo, "SELECT libelle FROM exercice WHERE id_exercice = ".$exercice_id." LIMIT 1", "fetch");
        $choix = explode(",",$question["choix"]);
        echo "<h1>".htmlentities($exercice['libelle'])."</h1>
            <p style='margin-bottom:30px;'>$nextQuestion. " .htmlentities($question['question']). "</p>
            <form role='form' name='quizform' action='" . WEBROOT . "exercices/" . $exercice_id . "' method='post'>
                <input name='starttime' value='1/15/2018 3:09:04 AM' type='hidden'>
                <input name='answers' value='" . implode($answers) . "' type='hidden'>
                <input name='qnumber' value='$nbQuestions' type='hidden'>";
        foreach ($choix as $key => $choi) {
            echo "<div class='radio'><label><input name='quizz' value='$key' type='radio'>".htmlentities($choi)."</label></div>";
        }
                echo "<br>
                <input name='next' value=' Suivant ' type='submit'>
            </form>
            <div>Total $nbQuestions questions</div>";

    } else {

        $nbBonnesReponses = nbBonnesReponses($exercice_id, implode($answers));
        $percent = ($nbBonnesReponses*100/$nbQuestions);

        echo "<center><h2>Resultat:</h2>$nbBonnesReponses sur $nbQuestions<p><b>".$percent."%</b></p><p>".getSentenceResult($percent)."</p><p><b>Temps passé</b><br>0:27</p></center>
        <form role='form' target='_blank' action='".WEBROOT."resultat' method='post'>
            <input name='points' value='$nbBonnesReponses' type='hidden'>
            <input name='id' value='$exercice_id' type='hidden'>
            <input name='timespent' value='0:27' type='hidden'>
            <input name='answers' value='".implode($answers)."' type='hidden'>
    
            <table width='100%'><tbody><tr>
                    <td><input value='Vérifier les réponses' name='checkAnswers' type='submit'></td>
                    <td align='right'><input value='Réessayer' onclick='window.location.href = window.location.href;' type='button'></td>
                </tr></tbody></table>
        </form>";
    }
    $content = ob_get_contents();
    ob_end_clean();
?>

<?php echo $content ?>