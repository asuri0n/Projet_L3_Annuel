<?php
$exercices = getArrayFrom($pdo, "SELECT id_exercice, ue_num, sem_id, matieres.libelle as mlib, exercice.libelle as exlib, date FROM exercice JOIN matieres USING (id_matiere)", "fetchAll");

ob_start();
foreach ($exercices as $exercice){
    echo "<tr>";
    echo "<td>".$exercice['id_exercice']."</td>";
    echo "<td>".$exercice['ue_num']."</td>";
    echo "<td>".$exercice['sem_id']."</td>";
    echo "<td>".$exercice['mlib']."</td>";
    echo "<td>".$exercice['exlib']."</td>";
    echo "<td><a href='".WEBROOT."exercices/".$exercice['id_exercice']."/1' class=\"btn btn-default btn-sm\" role=\"button\">
          <span class=\"glyphicon glyphicon-arrow-right\"></span> Faire l'exercice
        </a></td>";
    echo "</tr>";
}
$content = ob_get_contents();
ob_end_clean();
?>

<!-- Main component for a primary marketing message or call to action -->
<div class="jumbotron">
    <h1>Bienvenue</h1>
    <p>Ce site a pour but d'améliorer vos connaissances sur les différentes matières étudiées à l'UFR Science de Caen.<br></p>

    <p style="font-size: small">Ce projet est un projet annuel développé par Lounis Bibi et Nathan Chevalier pendant l'année de L3 Informatique 2017-2018</p>
</div>

<table class="table table-striped">
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
    <?php echo $content ?>
    </tbody>
</table>
