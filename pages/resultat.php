<?php

if(!isset($_POST['checkAnswers']) or !isset($_POST['points']) or !is_numeric($_POST['points']) or !isset($_POST['id']) or !is_numeric($_POST['id']) or !isset($_POST['timespent']) or !isset($_POST['answers']) or !is_numeric($_POST['answers'])) {
    $_SESSION['error'] = "Erreur, retour a l'accueil";
    session_write_close();
    header('location: ' . WEBROOT . 'accueil');
}

$points = $_POST['points'];
$id_exercice = $_POST['id'];
$answers = str_split($_POST['answers']);

$questions = getArrayFrom($pdo, "SELECT question, choix, reponses FROM questions WHERE id_exercice = $id_exercice", "fetchAll");

$total = sizeof($questions);
$timespent = $_POST['timespent'];

$exerciceInfo = getArrayFrom($pdo, "SELECT exercice.libelle FROM exercice WHERE id_exercice = $id_exercice", "fetch");
$exeTitre = $exerciceInfo["libelle"];

?>

<form>
    <table width="100%" cellspacing="0" border="0">
        <tbody><tr>
            <th class="front" align="left"><h1><?php echo $exeTitre ?></h1></th>
            <th class="front" align="right">Points: <?php echo $points; ?> sur <?php echo $total; ?></th>
        </tr>
        </tbody></table>
    <?php
        foreach ($answers as $key => $answer){

                ?>
    <p>
        <b><?php echo ($key+1).". ".$questions[$key]['question']; ?></b>
    </p>
    <p>
        <b>You answered:</b>
    </p>
     <?php echo explode(',',$questions[$key]['choix'])[$answer]?>
    <?php if(!in_array($answer,explode(',',$questions[$key]['reponses']))){ ?>
    <p style="color:red">
        &nbsp;Mauvaise réponse!
    </p>
    <?php } else { ?>
                <p style="color:green">
                    &nbsp;Bonne réponse!
                </p>
        <?php } ?>
    <hr>
       <?php }?>


    <br>Temps passé: <?php echo $timespent; ?>
</form>