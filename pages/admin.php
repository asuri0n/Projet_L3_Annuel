<?php
 if(isset($_POST['addExercice']))
     include 'addExercice.php';

?>


<form method="POST" action="" class="form-signin">
    <label> Ajouter un exercice de
        <input type="number" name="inputNbQuestions" value="0" min="0" max="10">
    </label> questions<br>
    <button name="addExercice" class="btn btn-lg btn-primary btn-block" type="submit">Ajouter</button>
</form>