<?php
/**
 * Created by PhpStorm.
 * User: Nathan
 * Date: 03/03/2018
 * Time: 19:05
 */

// Requete principale pour récuperer les données des exercices.
$query = "SELECT id_exercice, d.libelle as diplome, a.num_annee as annee, s.num_semestre as semestre, code_ue, code_ec, m.libelle as mlib, exercice.enonce as exlib, date
        FROM exercice
        JOIN matieres m ON exercice.id_matiere = m.id_matiere
        JOIN diplomes d ON m.code_diplome = d.code_diplome
        JOIN annees a ON m.code_annee = a.code_annee
        JOIN semestres s ON m.code_semestre = s.code_semestre";

// Si le paramètre Diplome a été donné, on vérifie si il n'y a pas de chars spéciaux ou entités HTML
if(isset($params[1]) and !empty($params[1])){
    $diplome = $params[1];
    $diplome = htmlspecialchars($diplome);
    $diplome = htmlentities($diplome);

    $query .= " WHERE d.libelle = '$diplome'";
}
// Pareil pour le paramètre Année
if(isset($params[2]) and !empty($params[2])){
    $annee = $params[2];
    $annee = htmlspecialchars($annee);
    $annee = htmlentities($annee);

    // La requete SQL se forme différement si le paramètre diplome a été saisi ou non
    if(isset($diplome))
        $query .= " and a.num_annee = $annee";
    else
        $query .= " WHERE d.num_annee = '$annee'";
}
$exercices = getArrayFrom( $query, "fetchAll");

ob_start();
// Pour chaque exercices on créer une nouvelle ligne
foreach ($exercices as $exercice){
    echo "<tr>";
    echo "<td>".ucfirst($exercice['diplome'])."</td>";
    echo "<td>Licence ".$exercice['annee']."</td>";
    echo "<td>Semestre ".$exercice['semestre']."</td>";
    echo "<td>".$exercice['mlib']."</td>";
    echo "<td>".$exercice['exlib']."</td>";
    echo "<td><a href='".WEBROOT."exercices/".$exercice['id_exercice']."' class=\"btn btn-default btn-sm ".(($exercice['annee']>2 and !isset($_SESSION['Auth']))?'disabled':"")." \" role=\"button\">
          <span class=\"glyphicon glyphicon-arrow-right\"></span> Faire l'exercice
        </a></td>";
    echo "</tr>";
}
$content = ob_get_contents(); // On récupère le buffer et on le mets dans une variable.
ob_end_clean();

// Message d'information si la personne n'est pas connecté. Elle n'a pas accès a tout les niveaux d'exercice.
if(!isset($_SESSION['Auth'])){
    echo "<div class=\"alert alert-danger\">
          <strong>Attention!</strong> Vous n'êtes actuellement pas connecté, vous pouvez seulement acceder aux exercices de L1 Math et Info.
        </div>";
}
?>

<table id="example" class="table table-striped table-bordered" width="100%" cellspacing="0" data-order='[[ 0, "asc" ]]' data-page-length='10'>
    <thead>
    <tr>
        <th>Diplome</th>
        <th>Année</th>
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
