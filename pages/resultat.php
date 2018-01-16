<?php

if(!isset($_POST['checkAnswers']) or !isset($_POST['points']) or !is_numeric($_POST['points']) or !isset($_POST['percentPoints']) or !is_numeric($_POST['percentPoints']) or !isset($_POST['id']) or !is_numeric($_POST['id']) or !isset($_POST['timespent']) or !isset($_POST['answers']) or !is_numeric($_POST['answers'])) {
    $_SESSION['error'] = "Erreur, retour a l accueil";
    session_write_close();
    header('location: ' . WEBROOT . 'accueil');
}

$points = $_POST['points'];
$percentPoints = $_POST['percentPoints'];
$id_exercice = $_POST['id'];
$total = 100*$points/$percentPoints;
$answers = $_POST['answers'];
$timespent = $_POST['timespent'];

?>

<form>
    <table width="100%" cellspacing="0" border="0">
        <tbody><tr>
            <th class="front" align="left"><h1>titre</h1></th>
            <th class="front" align="right">Points: <?php echo $points; ?> sur <?php echo $total; ?></th>
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

    <br>Time spent: <?php echo $timespent; ?>
</form>