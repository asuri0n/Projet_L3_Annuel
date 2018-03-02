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
                <li <?php if ($params[0] == 'exercices' or $params[0] == 'resultat') {echo 'class="active"';} ?> ><a href="<?php echo WEBROOT ?>exercices">Exercices</a></li>

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="#">Action</a></li>
                        <li><a href="#">Another action</a></li>
                        <li><a href="#">Something else here</a></li>
                        <li role="separator" class="divider"></li>
                        <li class="dropdown-header">Nav header</li>
                        <li><a href="#">Separated link</a></li>
                        <li><a href="#">One more separated link</a></li>
                    </ul>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <?php if(isset($_SESSION['Auth'])) { ?>
                    <?php if(isset($_SESSION['Auth']["isTeacher"])) { ?>
                        <li <?php if ( $params[0] == 'admin') {echo 'class="active"';} ?> ><a href="<?php echo WEBROOT ?>admin">Gestion</a></li>
                    <?php } ?>
                        <li <?php if ($params[0] == 'profil') {echo 'class="active"';} ?> ><a href="<?php echo WEBROOT ?>profil"><?php echo $_SESSION['Auth']['givenname'] ?></a></li>
                    <li <?php if ($params[0] == 'signout') {echo 'class="active"';} ?> ><a href="<?php echo WEBROOT ?>signout">Se déconnecter</a></li>
                <?php } else { ?>
                    <li <?php if ($params[0] == 'login') {echo 'class="active"';} ?> ><a href="<?php echo WEBROOT ?>login">Se connecter</a></li>
                <?php } ?>
            </ul>
        </div><!--/.nav-collapse -->
    </div><!--/.container-fluid -->
</nav>
