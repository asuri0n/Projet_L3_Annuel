<?php
/**
 * Created by PhpStorm.
 * User: asuri
 * Date: 16/01/2018
 * Time: 13:34
 */


// TODO: FAIRE UNE SEULE PAGE MODIFIER ET CREER


if(!isset($_POST['modifyExercice']) or !is_numeric($_POST['inputIdExercice']))
    header('location: accueil');

$exercice_id = $_POST['inputIdExercice'];
$exercice = getArrayFrom($pdo, "SELECT enonce, id_matiere FROM exercice WHERE exercice.id_exercice = $exercice_id", "fetch");




//TODO: faure selon le nb de questions de la page et pas de la base
$questions = getArrayFrom($pdo, "SELECT id_question, question, type_question.libelle typelib, choix, reponses FROM questions JOIN type_question USING (id_type) WHERE id_exercice = $exercice_id", "fetchAll");




// TODO: A concatener en 1 requete
$matieres = getArrayFrom($pdo, "SELECT id_matiere, ue_num, sem_id, libelle FROM matieres", "fetchAll");
$type_question = getArrayFrom($pdo, "SELECT id_type, libelle FROM type_question", "fetchAll");

if(isset($_POST['modifyExercice']) and isset($_POST['inputTitre']) and isset($_POST['inputMatiere']) and isset($_POST['inputTitreQuestion']) and isset($_POST['typeQ']) and isset($_POST['repQ']) and isset($_POST['bonneRep']))
{
    // TODO: changer plus tard avec du JS
    $nbReponses = 4;

    $nbQuestions = sizeof($questions);

    // TODO: VERIFIER SI redQ et bonneRep ont bien le nb de réponses
    if(sizeof($_POST['inputTitreQuestion']) == $nbQuestions and sizeof($_POST['typeQ']) == $nbQuestions and sizeof($_POST['repQ']) == $nbQuestions and sizeof($_POST['bonneRep']) == $nbQuestions) {
        $inputMatiere = $_POST['inputMatiere'];
        $inputTitre = $_POST['inputTitre'];
        $inputTitreQuestion = $_POST['inputTitreQuestion'];
        $typeQ = $_POST['typeQ'];
        $repQ = $_POST['repQ'];
        $bonneRep = $_POST['bonneRep'];

        if ($insert_stmt = $pdo->prepare("UPDATE exercice set id_matiere = ?, niv_etude = 1, enonce = ? WHERE id_exercice = $exercice_id"))
        {
            if ($insert_stmt->execute(array($inputMatiere, $inputTitre)))
            {
                foreach ($questions as $key => $question)
                {
                    if ($insert_stmt = $pdo->prepare("UPDATE questions set question = ?, id_type = ?, choix = ?, reponses = ? WHERE id_question = ".$question['id_question']))
                    {
                        $stringRep = "";
                        foreach ($repQ[$key] as $rep){
                            $stringRep.=$rep.",";
                        }
                        $stringRep = substr($stringRep, 0, -1);

                        $stringBRep = $bonneRep[$key];

                        //TODO : POUR LA VERSION AVEC CHECK BOX : Faire différement
                        /*$stringBRep = "";
                        foreach ($bonneRep[$key] as $key2 => $bRep){
                            $stringBRep.=$key2.",";
                        }
                        $stringBRep = substr($stringBRep, 0, -1);*/

                        if (!$insert_stmt->execute(array($inputTitreQuestion[$key], $typeQ[$key]+1, $stringRep, $stringBRep))) {
                            $error = true;
                        }
                    }
                }
                if(isset($error))
                    $_SESSION['error'] = "Erreur lors de la modification de l'exercice";
                else
                    $_SESSION['success'] = "Exercice mofidié";
                session_write_close();
                header('location: admin');
            }
        }
    } else {
        echo "erreur ";
    }
}
?>

<div style="background-color: cornsilk; padding:20px">
<form method="POST" class="form-signin">
    <input name="inputTitre" class="form-control" placeholder="Titre" type="text" value="<?php echo $exercice['enonce'] ?>">
    <div class="form-group">
        <label for="sel1">Matière :</label>
        <select class="form-control" id="sel1" name="inputMatiere">
            <?php
                foreach ($matieres as $matiere){
                    echo "<option value='".$matiere['id_matiere']."' ".($matiere['id_matiere'] == $exercice['id_matiere'] ? 'selected' : '')." >UE".$matiere['ue_num']." SEM".$matiere['sem_id']." : ".$matiere['libelle']."</option>";
                }
            ?>
        </select>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
            <tr>
                <th>Questions</th>
                <th>Type</th>
                <th>Réponses</th>
            </tr>
            </thead>
            <tbody>
                <?php foreach ($questions as $i => $question){
                    echo "<tr>";
                    echo "<td><input name='inputTitreQuestion[$i]' class='form-control' placeholder='Titre question ".($i+1)."' type='text' value='".$question['question']."'></td>";
                    echo "<td><select class=\"form-control\" id=\"sel1\" name='typeQ[$i]'>";
                        foreach ($type_question as $key => $type) {
                            echo "<option value='$key' ".($type['libelle'] == $question['typelib'] ? 'selected' : '').">".$type['libelle']."</option>";
                        }
                    echo "</select></td>";
                    echo "<td>";
                    $choix = explode(',',$question['choix']);
                    foreach ($choix as $key => $choi) {
                        $results = explode(',',$question['reponses']);
                        //TODO : POUR LA VERSION AVEC CHECK BOX : AJOUTER [] APRES bonneRep[$i] !!!
                        echo "<input name='repQ[$i][]' class='form-control' type='text' value='$choi'><input name='bonneRep[$i]' type='radio' ".(in_array($key, $results) ? 'checked' : '')." value='$key'> ";
                    }
                    echo "</td></tr>";
                }?>
                <input type="number" name="inputIdExercice" value="<?php echo $exercice_id; ?>" hidden>
            </tbody>
        </table>
    </div>

    <button name="modifyExercice" class="btn btn-lg btn-primary btn-block" type="submit">Modifier</button>
</form>
</div>