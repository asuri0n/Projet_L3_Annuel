<?php
/**
 * Created by PhpStorm.
 * User: Nathan
 * Date: 03/03/2018
 * Time: 19:05
 */

$exercices = getArrayFrom($pdo, "SELECT id_exercice, ue_num, sem_id, matieres.libelle as mlib, exercice.enonce as exlib, date FROM exercice JOIN matieres USING (id_matiere)", "fetchAll");

ob_start();
foreach ($exercices as $exercice){
    echo "<tr>";
    echo "<td>".$exercice['id_exercice']."</td>";
    echo "<td>".$exercice['ue_num']."</td>";
    echo "<td>".$exercice['sem_id']."</td>";
    echo "<td>".$exercice['mlib']."</td>";
    echo "<td>".$exercice['exlib']."</td>";
    echo "<td><a href='".WEBROOT."exercices/".$exercice['id_exercice']."' class=\"btn btn-default btn-sm ".(($exercice['sem_id']>2 and !isset($_SESSION['Auth']))?'disabled':"")." \" role=\"button\">
          <span class=\"glyphicon glyphicon-arrow-right\"></span> Faire l'exercice
        </a></td>";
    echo "</tr>";
}
$content = ob_get_contents();
ob_end_clean();

?>

<table id="example" class="table table-striped table-bordered" width="100%" cellspacing="0">
    <thead>
    <tr>
        <th>Numéro</th>
        <th>Unité d'Enseignement</th>
        <th>Semestre</th>
        <th>Matière</th>
        <th>Titre</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    <?= $content ?>
    </tbody>
</table>
