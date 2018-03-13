<?php
/**
 * Created by PhpStorm.
 * User: asuri
 * Date: 16/01/2018
 * Time: 13:34
 */

if(!isset($_POST['addExercice']) or !is_numeric($_POST['inputNbQuestions']))
    header('location: accueil');

$nbQuestions = $_POST['inputNbQuestions'];

$matieres = newSQLQuery( "SELECT id_matiere, code_diplome, code_annee, code_semestre, code_ue, code_ec, libelle FROM matieres", "select","fetchAll");
$type_question = newSQLQuery( "SELECT id_type, libelle FROM type_question", "select","fetchAll");

$error = false;
if(isset($_POST['addExercice']) and isset($_POST['inputTitre']) and isset($_POST['inputMatiere']) and isset($_POST['inputTitreQuestion']) and isset($_POST['typeQ']) and isset($_POST['repQ']) and isset($_POST['bonneRep']))
{
    // TODO: changer plus tard avec du JS
    $nbReponses = 4;

    if(sizeof($_POST['inputTitreQuestion']) == $nbQuestions and sizeof($_POST['typeQ']) == $nbQuestions and sizeof($_POST['repQ']) == $nbQuestions and sizeof($_POST['bonneRep']) == $nbQuestions) {
        $inputMatiere = $_POST['inputMatiere'];
        $inputTitre = $_POST['inputTitre'];
        $inputTitreQuestion = $_POST['inputTitreQuestion'];
        $typeQ = $_POST['typeQ'];
        $repQ = $_POST['repQ'];
        $bonneRep = $_POST['bonneRep'];

        // Insertion de l'exercice
        $stmt1 = newSQLQuery("INSERT INTO exercice (id_matiere, enonce, date) VALUES (?, ?, NOW())", "insert", null, null, array($inputMatiere, htmlspecialchars($inputTitre)));
        if (!$error and $stmt1)
        {

            $lastId = $pdo->lastInsertId();
            // Pour chaque questions de l'exercice
            foreach ($inputTitreQuestion as $key => $question)
            {
                /**
                 *  Vérification si il n'y a pas des choix vides
                 */
                if(is_array($repQ[$key])) {
                    $newRepQ = array();
                    $id = 0;
                    foreach ($repQ[$key] as $rep){
                        if(!empty($rep)){
                            $newRepQ[$id] = $rep;
                            $id++;
                        }
                    }
                }
                $repQ[$key] = $newRepQ;

                /**
                 *  Création de la chaine de caractère des bonnes réponses
                 */
                if(isset($bonneRep[$key]) and is_array($bonneRep[$key])){
                    $id_choix_bonn_rep = "";
                    foreach ($bonneRep[$key] as $rep){
                        $id_choix_bonn_rep.=$rep.",";
                    }
                    $id_choix_bonn_rep = substr($id_choix_bonn_rep, 0, -1);
                } else if(is_string($bonneRep[$key]))
                    $id_choix_bonn_rep = $bonneRep[$key];

                // TODO: Commentaires et justifications
                // Ajout de la question et son type
                $stmt2 = newSQLQuery("INSERT INTO questions (id_exercice, question, id_type) VALUES (?, ?, ?)", "insert", null, null, array($lastId, addslashes($inputTitreQuestion[$key]), $typeQ[$key]+1));
                if(!$error and $stmt2)
                {
                    $lastIdQuestion = $pdo->lastInsertId();
                    if(is_array($repQ[$key])) {
                        foreach ($repQ[$key] as $rep) {
                            if (!$error)
                                $stmt3 = newSQLQuery("INSERT INTO choix (id_question, choix) VALUES (?, ?)", "insert", null, null, array($lastIdQuestion, addslashes($rep)));
                            if (!$stmt3)
                                $error = true;
                        }
                    } else
                        $stmt3 = newSQLQuery("INSERT INTO choix (id_question, choix) VALUES (?, ?)", "insert", null, null, array($lastIdQuestion, addslashes($repQ[$key][$bonneRep[$key]])));

                    if (!$error and $stmt3)
                    {
                        // TODO revoir le == 2
                        if($typeQ[$key] == 2 ) {
                            if(is_array($repQ[$key]))
                                if(isset($repQ[$key][0]))
                                    $rep = $repQ[$key][0];
                                else
                                    $rep = "";
                            $stmt4 = newSQLQuery("INSERT INTO reponses (id_question, id_choix_bonn_rep, reponse_fixe) VALUES (?, NULL, ?)", "insert", null, null, array($lastIdQuestion, $rep));
                        } else if(isset($id_choix_bonn_rep) and $id_choix_bonn_rep != "")
                            $stmt4 = newSQLQuery("INSERT INTO reponses (id_question, id_choix_bonn_rep, reponse_fixe) VALUES (?, ?, null)", "insert", null, null, array($lastIdQuestion, $id_choix_bonn_rep));
                        else
                            $error = true;
                    } else
                        $error = true;
                } else
                    $error = true;
            }
        } else
            $error = true;
    } else
        $error = true;

    if($error)
        $_SESSION['error'] = "Erreur lors de l'ajout de l'exercice";
    else
        $_SESSION['success'] = "Exercice ajouté";
}
?>

<div style="background-color: cornsilk; padding:20px">
<form method="POST" class="form-signin">
    <div class="form-group">
        <label for="sel1">Enoncé de l'exercice :</label>
        <input name="inputTitre" class="form-control" placeholder="Enoncé de l'exercice" type="text">
    </div>
    <div class="form-group">
        <label for="sel1">Matière :</label>
        <select class="form-control" id="sel1" name="inputMatiere">
            <?php
                foreach ($matieres as $matiere){
                    echo "<option value='".$matiere['id_matiere']."'>".$matiere['code_semestre'].$matiere['code_ue'].$matiere['code_ec']." : ".$matiere['libelle']."</option>";
                }
            ?>
        </select>
    </div>
    <div class="form-group">
        <label for="sel1">Liste des questions :</label>
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>Questions</th>
                    <th>Type</th>
                    <th>Choix (Cochez la bonne réponse)</th>
                </tr>
                </thead>
                <tbody>
                    <?php for($i=0; $i<$nbQuestions ; $i++){
                        echo "<tr>";
                            echo "<td style='width: 30%;'><input name='inputTitreQuestion[$i]' class='form-control' placeholder='Titre question ".($i+1)."' type='text'></td>";
                            echo "<td style='width: 20%;'><select class='form-control' id='sel1' name='typeQ[$i]'>";
                                foreach ($type_question as $key => $type) {
                                    echo "<option value='$key' id='".$i."inputType".$key."' onclick='changerInput($i,$key)'>".$type['libelle']."</option>";
                                }
                            echo "</select></td>";

                            //TODO : POUR LA VERSION AVEC CHECK BOX : AJOUTER [] APRES bonneRep[$i] !!!
                            echo "<td>";
                            for ($i2 = 0; $i2 < 4; $i2++)
                                echo "<span style='display: inline-block; width: 50%'><input name='repQ[$i][]' class='form-control' placeholder='Choix n°$i2' type='text' style='float:left; width: 85%'><input id='input$i' name='bonneRep[$i][]' type='checkbox' value='$i2' style='margin:0.5em 0.5em 0.5em 0.5em;'></span>";
                            echo "</td>";
                        echo "</tr>";
                    }?>
                    <input type="number" name="inputNbQuestions" value="<?= $nbQuestions ?>" hidden>
                </tbody>
            </table>
        </div>
    </div>

    <button name="addExercice" class="btn btn-lg btn-primary btn-block" type="submit">Ajouter</button>
</form>
</div><hr style="border-color: gray;">

<script>
    function changerInput(id,key){
        var input = $('input[id^=input'+id+']');
        if(key === 0){
            input.prop('type', 'checkbox');
            input.attr('name', 'bonneRep['+id+'][]');
        } else {
            input.prop('type', 'radio');
            input.attr('name', 'bonneRep['+id+']');
        }
    }
</script>