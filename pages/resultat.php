<?php

if(!isset($_POST['checkAnswers']) or !isset($_POST['points']) or !is_numeric($_POST['points']) or !isset($_POST['id']) or !is_numeric($_POST['id']) or !isset($_POST['timespent']) or !isset($_POST['answers'])) {
    $_SESSION['error'] = "Erreur, retour a l'accueil";
    session_write_close();
    header('location: ' . WEBROOT . 'accueil');
}

$points = $_POST['points'];
$id_exercice = $_POST['id'];

$reponses_user = json_decode($_POST['answers']);
$reponses_bdd = newSQLQuery( "SELECT id_question, enonce, question, id_choix_bonn_rep, reponse_fixe FROM reponses JOIN questions USING (id_question) JOIN exercice USING (id_exercice) WHERE id_question = ANY (SELECT id_question FROM questions WHERE id_exercice = ?)", "select", "fetchAll", "FETCH_ASSOC", $id_exercice);

$total = sizeof($reponses_bdd);
$timespent = $_POST['timespent'];
$exeTitre = $reponses_bdd[0]["enonce"];

$content = "";
foreach ($reponses_bdd as $key => $reponse_bdd)
{
    $erreur = false;
    $choix_bdd = newSQLQuery("SELECT id_choix, choix FROM choix WHERE id_question = ?", "select","fetchAll", "FETCH_ASSOC", $reponse_bdd['id_question']);
    $content .= "<p><b>".($key+1).". ".ucfirst(stripslashes($reponse_bdd['question']))."</b></p>";

    $content .= "<p>";
    // Si c'est un tableau, alors il faut récupérer les id des choix et afficher le libelle du choix
    if(is_array($reponses_user[$key]))
    {
        $content .= "<b>Vos réponses:</b><br>";
        foreach ($choix_bdd as $keychoix => $choi_bdd)
            foreach ($reponses_user[$key] as $reponse_user)
                if ($keychoix == $reponse_user)
                    $content .= stripslashes($choi_bdd["choix"]) . "<br>";

        // Vérification erreurs
        $rep_bdd_array = explode(',',$reponse_bdd["id_choix_bonn_rep"]);
        foreach ($rep_bdd_array as $key2 => $rep_bdd)
            if (!array_key_exists($key2, $reponses_user[$key]) or $rep_bdd != $reponses_user[$key][$key2])
                $erreur = true;
    }
    // Sinon si c'est pas un tableau mais que le type de réponse est un choix unique, alors il faut récuperer aussi le libelle du choix
    else if($reponse_bdd["id_choix_bonn_rep"] != null)
    {
        $content .= "<b>Votre réponse:</b><br>";
        foreach ($choix_bdd as $keychoix => $choi_bdd)
            if($keychoix == $reponses_user[$key])
                $content .= stripslashes($choi_bdd['choix'])."<br>";

        // Vérification erreurs
        if($reponse_bdd["id_choix_bonn_rep"] != $reponses_user[$key])
            $erreur = true;
    }
    // Sinon on affiche le texte
    else if($reponse_bdd["reponse_fixe"] != null) {
        $content .= "<b>Votre réponse:</b><br>";
        $content .= stripslashes($reponses_user[$key])."<br>";

        // Vérification erreurs
        $repbdd = str_replace(' ', '', strtolower($reponse_bdd['reponse_fixe']));
        $repuser = str_replace(' ', '', strtolower($reponses_user[$key]));

        if ($repbdd != $repuser)
            $erreur = true;
    } else {
        $content .= "<p style='color:red'>Erreur lors de la vérification des réponses. Veuillez contacter un administrateur.</p>";
    }
    $content .= "</p>";

    if(!isset($erreur))
        $content .= "<p style='color:red'>Erreur lors de la vérification des réponses. Veuillez contacter un administrateur.</p>";
    else if($erreur)
    {
        $content .= "<p> <span style='color:red'>Mauvaise réponse!</span><br>";
        if($reponses_bdd[$key]['reponse_fixe'] != null)
            $content .= "<span style='color:green'>Bonne réponse : ".stripslashes($reponses_bdd[$key]['reponse_fixe'])."</span></p>";
        else if($reponses_bdd[$key]['id_choix_bonn_rep'] != null)
        {
            $rep_bdd_array = explode(',',$reponses_bdd[$key]['id_choix_bonn_rep']);
            $content .= "<span style='color:green'>Bonne réponse : ";
            foreach ($choix_bdd as $keychoix => $choi_bdd)
                foreach ($rep_bdd_array as $reponse_bdd)
                    if ($keychoix == intval($reponse_bdd))
                        $content .= stripslashes($choi_bdd["choix"]) . ", ";
            $content = substr($content, 0, -2); // Supprime le , et ' ' a la fin
            $content .= "</span></p>";
        }
    } else
        $content .= "<p style='color:green'>Bonne réponse!</p>";
    $content .= "<hr>";
}

$content .= "<br>Temps passé: $timespent";

?>


<form>
    <table width="100%" cellspacing="0" border="0">
        <tbody><tr>
            <th class="front" align="left"><h1><?php echo $exeTitre ?></h1></th>
            <th class="front" align="right">Points: <?php echo $points; ?> sur <?php echo $total; ?></th>
        </tr>
        </tbody>
    </table>
    <?= $content ?>
</form>