<?php
    // Vérification si l'exercice a bien été selectionné
    if(!isset($params[1]) or empty($params[1]) or !is_numeric($params[1])){
        $_SESSION['error'] = "Veuillez d abord cliquer sur un exercice";
        session_write_close();
        header('location: '.WEBROOT.'accueil');
    };

    // Récupère l'ID de l'exercice
    $exercice_id = $params[1];

    //
    // vérifications a chaque nouvelle affichage d'une question
    //
    if(isset($_POST['next'])){
        if(isset($_POST['answers']) and isset($_POST['qnumber']) and !empty($_POST['answers'] and !empty($_POST['qnumber']))){
            $nbQuestions = $_POST['qnumber'];
            $answers = str_split($_POST['answers']);
            if(isset($_POST['quizz']) and is_numeric($_POST['quizz'])){
                // On rajoute la réponse dans la chaine
                foreach ($answers as $key => $answer) {
                    if($answer == '0') {
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
                    if($answer != '0')
                        $cpt++;
                }
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
            $answers .= "0";
        $answers = str_split($answers);
    }

    $question = getArrayFrom($pdo, "SELECT id_question, question, type_question.libelle as typelib, choix, reponses FROM questions JOIN type_question USING (id_type) WHERE id_exercice = ".$exercice_id." ORDER BY id_question ASC LIMIT ".($nextQuestion-1).", 1 ", "fetch");





    $exercice = getArrayFrom($pdo, "SELECT libelle FROM exercice WHERE id_exercice = ".$exercice_id." LIMIT 1", "fetch");

    $choix = explode(",",$question["choix"]);

    ob_start();
    echo "<p style='margin-bottom:30px;'>$nextQuestion. ".$question['question']."</p>";
    echo "<form role='form' name='quizform' action='".WEBROOT."exercices/".$exercice_id."/1' method='post'>";
    echo "<input name='starttime' value='1/15/2018 3:09:04 AM' type='hidden'>";
    echo "<input name='answers' value='".implode($answers)."' type='hidden'>";
    echo "<input name='qnumber' value='$nbQuestions' type='hidden'>";
        foreach ($choix as $key => $choi){
            echo "<div class='radio'><label><input name='quizz' value='$key' type='radio'> $choi</label></div>";
        }
    echo "<br>";
    echo "<input name='next' value=' Next ' type='submit'>";
    echo "</form>";
    $content = ob_get_contents();
    ob_end_clean();
?>




<div class="w3-col l10 m12" id="main">
    <h1><?php echo $exercice['libelle'] ?></h1>
    <?php echo $content ?>
    <div class="w3-padding-16">
        <div class="w3-row">
            <div class="w3-col s6">Total 25 questions</div>
        </div>
    </div>

</div>

<br>
<hr>
<br>

<div class="w3-padding w3-light-grey w3-large">
    <center><h2>Result:</h2>2 of 25<p><b>8%</b></p><p>You must study much harder!</p><p><b>Time Spent</b><br>0:27</p></center>
    <form role="form" target="_blank" action="result.asp" method="post">
        <input name="points" value="2" type="hidden">
        <input name="percentPoints" value="8" type="hidden">
        <input name="qtest" value="CSS" type="hidden">
        <input name="timespent" value="0:27" type="hidden">
        <input name="answers" value="1311124132100000000000000" type="hidden">

        <table width="100%"><tbody><tr>
                <td><input class="w3-btn w3-orange w3-text-white w3-large" value="Check your answers" type="submit"></td>
                <td align="right"><input class="w3-btn w3-orange w3-text-white w3-large" value="Try again" onclick="location='quiztest.asp?qtest=CSS'" type="button"></td>
            </tr></tbody></table>
    </form>
</div>

<br>
<hr>
<br>

<div class="w3-col l10 m12" id="main">
    <div id="mainLeaderboard" style="overflow:hidden;">
        <!-- MainLeaderboard-->
        <div id="div-gpt-ad-1422003450156-2">
            <script type="text/javascript">googletag.cmd.push(function() { googletag.display('div-gpt-ad-1422003450156-2'); });</script>
        </div>
    </div>
    <h1>W3Schools <span class="color_h1">CSS</span> Quiz</h1>
    <form>
        <table class="front" width="100%" cellspacing="0" border="0">
            <tbody><tr>
                <th class="front" align="left">CSS QUIZ</th>
                <th class="front" align="right">Points: 2 out of 25</th>
            </tr>
            </tbody></table>
        <p>
            <b>1. What does CSS stand for?</b>
        </p>
        <p>
            <b>You answered: </b>
        </p>
        Colorful Style Sheets
        <p style="color:red">
            &nbsp;Wrong Answer!
        </p>
        <hr>

        <br>
        <table class="front" width="100%" cellspacing="0" border="0">
            <tbody><tr>
                <th class="front" align="left">By W3Schools</th>
                <th class="front" align="right">Time spent: 0:27&nbsp;</th>
            </tr>
            </tbody></table>
    </form>

</div>