<?php
    if(!isset($_SESSION['Auth']['isStudent'])){
        $_SESSION['error'] = "Vous n'avez pas acces a cette page!";
        session_write_close();
        header('location: '.WEBROOT.'accueil');
    }

    echo $titre
?>

<div>
    <ul class='nav nav-tabs' role='tablist'>
        <li role="presentation" class="active">
            <a href="#home" aria-controls="home" role="tab" data-toggle="tab">Home</a>
       </li>
        <li role="presentation">
            <a class='nav-link' id='profile-tab' data-toggle='tab' href='#profile' role='tab' aria-controls='profile' aria-selected='false'>Mes scores</a>
        </li>
    </ul>

    <div class='tab-content'>
        <div class='tab-pane active' id='home' role='tabpanel'>
            <br>
            <?= $tab1 ?>
            <br>
        </div>
        <div class='tab-pane' id='profile' role='tabpanel'>
            <br>
            <?= $tab2 ?>
            <br>
        </div>
    </div>
</div>