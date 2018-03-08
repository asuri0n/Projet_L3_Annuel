<?php
    if(!isset($_SESSION['Auth']['isTeacher']) and !isset($_SESSION['Auth']['isAdmin'])){
        $_SESSION['error'] = "Vous n'avez pas acces a cette page!";
        session_write_close();
        header('location: '.WEBROOT.'accueil');
    }

    if(isset($_POST['addExercice']))
        include 'addExercice.php';

    if(isset($_POST['modifyExercice']))
        include 'modifyExercice.php';

    $exercicesListe = getArrayFrom( "SELECT id_exercice, enonce FROM exercice", "fetchAll", 'FETCH_BOTH');

?>

<?= $titre ?>

<form method="POST" action="" class="form-signin">
    <label> Ajouter un exercice de
        <input type="number" name="inputNbQuestions" value="0" min="0" max="10">
    </label> questions<br>
    <button name="addExercice" class="btn btn-lg btn-primary btn-block" type="submit">Ajouter</button>
</form>
<hr>
<form method="POST" action="" class="form-signin">
    <div class="form-group">
        <label> Modifier l'exercice suivant : </label>
        <select class="form-control" name="inputIdExercice">
            <?php foreach ($exercicesListe as $exercice) {
                echo "<option value='$exercice[0]'>$exercice[0] - $exercice[1]</option>";
            } ?>
        </select>
    </div>
    <button name="modifyExercice" class="btn btn-lg btn-primary btn-block" type="submit">Modifier</button>
</form>