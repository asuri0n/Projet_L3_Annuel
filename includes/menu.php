<!-- Static navbar -->
<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?php echo WEBROOT ?>accueil/">Département Maths-Info</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li <?php if ($params[0] == 'accueil') {echo 'class="active"';} ?> ><a href="<?php echo WEBROOT ?>accueil/">Accueil</a></li>
                <li <?php if ($params[0] == 'exercices' or $params[0] == 'resultat') {echo 'class="active"';} ?> class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Exercices <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li class="dropdown-submenu">
                            <a href="#">Informatique <span class="caret caret-right"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="#">Licence 1</a></li>
                                <li><a href="#">Licence 2</a></li>
                                <li><a href="#">Licence 3</a></li>
                            </ul>
                        </li>
                        <li class="dropdown-submenu">
                            <a href="#">Mathématique <span class="caret caret-right"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="#">Licence 1</a></li>
                                <li><a href="#">Licence 2</a></li>
                                <li><a href="#">Licence 3</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <?php if(isset($_SESSION['Auth'])) { ?>
                    <?php if(isset($_SESSION['Auth']["isTeacher"])) { ?>
                        <li <?php if ($params[0] == 'espace') {echo 'class="active"';} ?> ><a href="<?php echo WEBROOT ?>espace/teacher">Espace Prof</a></li>
                    <?php } ?>
                    <?php if(isset($_SESSION['Auth']["isAdmin"])) { ?>
                        <li <?php if ($params[0] == 'espace') {echo 'class="active"';} ?> ><a href="<?php echo WEBROOT ?>espace/admin">Espace Admin</a></li>
                    <?php } ?>
                    <?php if(isset($_SESSION['Auth']["isStudent"])) { ?>
                        <li <?php if ($params[0] == 'espace') {echo 'class="active"';} ?> ><a href="<?php echo WEBROOT ?>espace/student">Espace Etudiant</a></li>
                    <?php } ?>
                    <li <?php if ($params[0] == 'signout') {echo 'class="active"';} ?> ><a href="<?php echo WEBROOT ?>signout">Se déconnecter</a></li>
                <?php } else { ?>
                    <li <?php if ($params[0] == 'login') {echo 'class="active"';} ?> ><a href="<?php echo WEBROOT ?>login">Se connecter</a></li>
                <?php } ?>
            </ul>
        </div><!--/.nav-collapse -->
    </div><!--/.container-fluid -->
</nav>
