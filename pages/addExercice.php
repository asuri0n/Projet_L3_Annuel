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

$matieres = getArrayFrom( "SELECT id_matiere, ue_num, sem_id, libelle FROM matieres", "fetchAll");
$type_question = getArrayFrom( "SELECT id_type, libelle FROM type_question", "fetchAll");

if(isset($_POST['addExercice']) and isset($_POST['inputTitre']) and isset($_POST['inputMatiere']) and isset($_POST['inputTitreQuestion']) and isset($_POST['typeQ']) and isset($_POST['repQ']) and isset($_POST['bonneRep']))
{
    // TODO: changer plus tard avec du JS
    $nbReponses = 4;

    // TODO: VERIFIER SI redQ et bonneRep ont bien le nb de réponses
    if(sizeof($_POST['inputTitreQuestion']) == $nbQuestions and sizeof($_POST['typeQ']) == $nbQuestions and sizeof($_POST['repQ']) == $nbQuestions and sizeof($_POST['bonneRep']) == $nbQuestions) {
        $inputMatiere = $_POST['inputMatiere'];
        $inputTitre = $_POST['inputTitre'];
        $inputTitreQuestion = $_POST['inputTitreQuestion'];
        $typeQ = $_POST['typeQ'];
        $repQ = $_POST['repQ'];
        $bonneRep = $_POST['bonneRep'];

        if ($insert_stmt = $pdo->prepare("INSERT INTO exercice (id_matiere, niv_etude, enonce, date) VALUES (?, 1, ?, NOW())"))
        {
            if ($insert_stmt->execute(array($inputMatiere, $inputTitre)))
            {
                $lastId = $pdo->lastInsertId();
                foreach ($inputTitreQuestion as $key => $question)
                {
                    if ($insert_stmt = $pdo->prepare("INSERT INTO questions (id_exercice, question, id_type, choix, reponses) VALUES (?, ?, ?, ?, ?)"))
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

                        if ($insert_stmt->execute(array($lastId, $inputTitreQuestion[$key], $typeQ[$key]+1, $stringRep, $stringBRep))) {
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
    <input name="inputTitre" class="form-control" placeholder="Titre" type="text">
    <div class="form-group">
        <label for="sel1">Matière :</label>
        <select class="form-control" id="sel1" name="inputMatiere">
            <?php
                foreach ($matieres as $matiere){
                    echo "<option value='".$matiere['id_matiere']."'>UE".$matiere['ue_num']." SEM".$matiere['sem_id']." : ".$matiere['libelle']."</option>";
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
                <?php for($i=0; $i<$nbQuestions ; $i++){
                    echo "<tr>";
                    echo "<td><input name='inputTitreQuestion[$i]' class='form-control' placeholder='Titre question ".($i+1)."' type='text'></td>";
                    echo "<td><select class=\"form-control\" id=\"sel1\" name='typeQ[$i]'>";
                        foreach ($type_question as $key => $type) {
                            echo "<option value='$key'>".$type['libelle']."</option>";
                        }
                    echo "</select></td>";
                    //TODO : POUR LA VERSION AVEC CHECK BOX : AJOUTER [] APRES bonneRep[$i] !!!
                    echo "<td><input name='repQ[$i][]' class='form-control' type='text'><input name='bonneRep[$i]' type=\"radio\" value='0'>
                    <input name='repQ[$i][]' class='form-control' type='text'><input name='bonneRep[$i]' type=\"radio\" value='1'>
                    <input name='repQ[$i][]' class='form-control' type='text'><input name='bonneRep[$i]' type=\"radio\" value='2'>
                    <input name='repQ[$i][]' class='form-control' type='text'><input name='bonneRep[$i]' type=\"radio\" value='3'></td>";
                    echo "</tr>";
                }?>
                <input type="number" name="inputNbQuestions" value="<?php echo $nbQuestions; ?>" hidden>
            </tbody>
        </table>
    </div>

    <button name="addExercice" class="btn btn-lg btn-primary btn-block" type="submit">Ajouter</button>
</form>
</div><hr style="border-color: gray;">