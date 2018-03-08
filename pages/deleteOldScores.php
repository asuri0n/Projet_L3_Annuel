<?php

$deleteOldScores = newSQLQuery("DELETE sc.* FROM scores sc JOIN etudiants USING(id_etudiant) WHERE fin_inscription < now()", "delete");

if($deleteOldScores != null)
    if($deleteOldScores)
        $_SESSION['success'] = $deleteOldScores." scores nettoyés";
    else
        $_SESSION['error'] = "Pas de scores a nettoyer";
else
    $_SESSION['error'] = "Une erreur s'est produite";
