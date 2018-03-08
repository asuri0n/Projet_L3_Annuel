<?php

$bestScores = newSQLQuery("SELECT id_etudiant, id_exercice, enonce, MAX(resultat) as resultat FROM scores JOIN exercice USING (id_exercice) GROUP BY id_exercice, enonce", "select", "fetchAll", "FETCH_ASSOC");

$arrayContent = "";
foreach ($bestScores as $bestScore){
    $arrayContent .= "<tr>";
    $arrayContent .= "<td>".$bestScore['id_etudiant']."</td>";
    $arrayContent .= "<td>".$bestScore['id_exercice'].". ".$bestScore['enonce']."</td>";
    $arrayContent .= "<td>".$bestScore['resultat']."</td>";
    $arrayContent .= "</tr>";
}

?>

<div style="background-color: cornsilk; padding:20px">
    <div class="container">
        <h2>Meilleurs scores</h2>
        <p>Liste des meilleurs scores par exercice:</p>
        <table class="table table-striped" style="background-color: white; width: 93%;">
            <thead>
            <tr>
                <th>Etudiant</th>
                <th>Exercice</th>
                <th>Résultat</th>
            </tr>
            </thead>
            <tbody>
                <?= $arrayContent ?>
            </tbody>
        </table>
    </div>
</div>

