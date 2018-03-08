<?php

function sec_session_start() {
    $session_name = 'sec_session_id';   // Attribue un nom de session
    $secure = SECURE;
    // Cette variable empêche Javascript d’accéder à l’id de session
    $httponly = true;
    // Force la session à n’utiliser que les cookies
    if (ini_set('session.use_only_cookies', 1) === FALSE) {
        header("Location: ../error.php?err=Could not initiate a safe session (ini_set)");
        exit();
    }
    // Récupère les paramètres actuels de cookies
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params($cookieParams["lifetime"],
        $cookieParams["path"], 
        $cookieParams["domain"], 
        $secure,
        $httponly);
    // Donne à la session le nom configuré plus haut
    session_name($session_name);
    session_start();            // Démarre la session PHP 
    session_regenerate_id();    // Génère une nouvelle session et efface la précédente
}

/**
 * Connection d'un utilisateur
 *
 * @param String $email -> email of the user (lel)
 * @param String $password -> password of the user (lel)
 * @return true/false;
 */
function login($email, $password) {
	$pdo = SPDO::getInstance();
    if ($stmt = $pdo->prepare("SELECT persopass, email, password, salt FROM admins WHERE email = ? LIMIT 1"))
    {
        if ($stmt->execute(array($email)))
        {
            $row = $stmt->fetch(PDO::FETCH_BOTH);
            if ($row)
            {
                $persopass = $row[0];
                $email = $row[1];
                $db_password = $row[2];
                $salt = $row[3];

				// Hashe le mot de passe avec le salt unique
				$password = hash('sha512', $password . $salt);
                // Vérifie si les deux mots de passe sont les mêmes
                // Le mot de passe que l’utilisateur a donné.
                if ($db_password == $password) {
                    // Protection XSS car nous pourrions conserver cette valeur
                    $persopass = preg_replace("/[^0-9]+/", "", $persopass);
                    $_SESSION['Auth']['id'] = $persopass;
                    $_SESSION['Auth']['user'] = $email;
                    $_SESSION['Auth']['isAdmin'] = true;

                    return true;
                } else {
                    $_SESSION['error'] = "Mot de passe incorrect";
                    return false;
                }
			} else {
				$_SESSION['error'] = "L'utilisateur n'existe pas";
				return false;
			}
		} else {
			$_SESSION['error'] = "Erreur lors de la connexion";
			return false;
		}
	} else {
		$_SESSION['error'] = "Erreur lors de la connexion";
		return false;
	}
}

/**
 * Connection avec le serveur ldap
 *
 * @param String $ldapuser
 * @param String $ldappass
 * @return true/false;
 */
function connectionLdap($ldapuser, $ldappass)
{
    $ldapServer = "srv-ldap.iutc3.unicaen.fr";
    $ldapServerPort = 389;
    $ldaptree = 'uid=e' . $ldapuser . ',ou=people,dc=unicaen,dc=fr';

    // connect
    $ldapconn = ldap_connect($ldapServer, $ldapServerPort);

    if ($ldapconn)
    {
        ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
        // binding to ldap server
        $ldapbind = ldap_bind($ldapconn, $ldaptree, $ldappass);
        if($ldapbind)
        {
            $result = ldap_search($ldapconn, $ldaptree, "(cn=*)");
            if($result)
            {
                $data = ldap_get_entries($ldapconn, $result);

                $email = $data[0]['mail'][0];
                $displayname = $data[0]['displayname'][0];
                $givenname = $data[0]['givenname'][0];

                $diplome = explode(';',$data[0]['ucbncodeetape'][0])[1];
                $status = $data[0]['ucbnstatus'][0];

                $ufr = $data[0]['supannaffectation'][0];
                $elempedag = $data[0]['supannetuelementpedagogique'];

                // Si la personne est un étudiant
                if(strcmp($status,"ETUDIANT") == 0)
                {
                    // Si la personne un étudiant à l'UFR des Sciences
                    if(strcmp($ufr,UFRSCIENCES) == 0)
                    {
                        if (!updateAccount(0, $data))
                        {
                            $_SESSION['error'] = "[1400] Quelque chose s'est mal passé :(";
                            return false;
                        }

                        $_SESSION['Auth']['user'] = $ldapuser;
                        $_SESSION['Auth']['givenname'] = $givenname;
                        $_SESSION['Auth']['displayname'] = $displayname;
                        $_SESSION['Auth']['email'] = $email;
                        $_SESSION['Auth']['diplome'] = $diplome;

                        $elemnewarray = [];
                        $j=0;
                        // Pour chaque elem pédag trouvé via ldap
                        foreach ($elempedag as $elem)
                        {
                            if(!is_numeric($elem))
                            {
                                $str = explode('_', $elem)[1];
                                if (!preg_match("/INFOS[0-9]/", $str))
                                    $elemnewarray[$j] = explode('_', $elem)[1]; // composition element pédag : {UAI:0141408E}AE_INF6D ; On garde que la partie apres le _ donc on explode
                            }
                            $j++;
                        }
                        $_SESSION['Auth']['elempedag'] = $elemnewarray;

                        //!\\//!\\//!\\//!\\//!\\ POUR LE DEV //!\\//!\\//!\\//!\\//!\\
                        $_SESSION['Auth']['isStudent'] = true;
                        //!\\//!\\//!\\//!\\//!\\//!\\//!\\//!\\//!\\//!\\

                        return true;

                    } else {
                        $_SESSION['error'] = "Vous n'êtes pas un(e) étudiant(e) de l'UFR des Science";
                    }
                } else {

                    if (!updateAccount(1, $data)) {
                        $_SESSION['error'] = "[1400] Quelque chose s'est mal passé :(";
                        return false;
                    }

                    // Si la personne est un professeur
                    $_SESSION['Auth']['user'] = $ldapuser;
                    $_SESSION['Auth']['givenname'] = $givenname;
                    $_SESSION['Auth']['displayname'] = $displayname;
                    $_SESSION['Auth']['email'] = $email;
                    $_SESSION['Auth']['isTeacher'] = true;

                    return true;
                }
            }
        }
    } else {
        $_SESSION['error'] = "Could not connect to LDAP server";
    }
    return false;
}

/**
 * Mets a jour l'user dans la DB
 *
 * @param Boolean $isTeacher
 * @param array $data
 * @return true/false;
 */
function updateAccount($isTeacher,$data){
    $pdo = SPDO::getInstance();

    if($isTeacher) {
        //TODO : Pour les professeurs
    } else {
        // Si l'étudiant s'est déja connecté sur le site
        $etupass = $data[0]['uidnumber'][0];

        if (!getArrayFrom("SELECT * FROM etudiants WHERE id_etudiant = ".$etupass,"fetch")) {
            $fininscription = $data[0]['datefininscription'][0];
            $fininscription = DateTime::createFromFormat('Ymd', $fininscription)->format('Y-m-d');

            if ($insert_stmt = $pdo->prepare("INSERT INTO etudiants (id_etudiant, date_prem_conn, fin_inscription) VALUES (?, now(), ?)"))
                if ($insert_stmt->execute(array($etupass, $fininscription)))
                    return true;
        } else {
            return true;
        }
    }
    return false;
}

/**
 * Création d'un utilisateur
 *
 * @param String $username -> username of the user (lel)
 * @param String $password -> password of the user (lel)
 * @param mysql $pdo -> database object
 * @return true/false;
 */
function signup() {
    $pdo = SPDO::getInstance(); 
    if (isset($_POST['email'], $_POST['email2'], $_POST['password'], $_POST['password2'])) {
        // Nettoyez et validez les données transmises au script
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $email = filter_var($email, FILTER_VALIDATE_EMAIL);
        $email2 = filter_input(INPUT_POST, 'email2', FILTER_SANITIZE_EMAIL);
        $email2 = filter_var($email2, FILTER_VALIDATE_EMAIL);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) OR !filter_var($email2, FILTER_VALIDATE_EMAIL)) {          
            $_SESSION['error'] = "Adresse mail non valide";
            return false;
        }
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
        $password2 = filter_input(INPUT_POST, 'password2', FILTER_SANITIZE_STRING);

        if($email != $email2){                    
            $_SESSION['error'] = "Les emails ne correspondent pas.";
            return false;
        }

        if($password2 != $password){                    
            $_SESSION['error'] = "Les mots de passes ne correspondent pas.";
            return false;
        }

        // La forme du nom d’utilisateur et du mot de passe a été vérifiée côté client
        // Cela devrait suffire, car personne ne tire avantage
        // à briser ce genre de règles.
        //
     
        $prep_stmt = "SELECT id FROM admins WHERE email = ? LIMIT 1";
        $stmt = $pdo->prepare($prep_stmt);
     
        if ($stmt) {
            $stmt->execute(array($email));
            $row = $stmt->fetch();
            if ($row) {            
                $_SESSION['error'] = "Il existe déja un utilisateur avec le même email";
                return false;
            }
        } else {
            $_SESSION['error'] = "Erreur de base de données";
            return false;
        }
     
        // CE QUE VOUS DEVEZ FAIRE: 
        // Nous devons aussi penser à la situation où l’utilisateur n’a pas
        // le droit de s’enregistrer, en vérifiant quel type d’utilisateur essaye de
        // s’enregistrer.
     

        // Crée un salt au hasard
        $random_salt = hash('sha512', uniqid(openssl_random_pseudo_bytes(16), TRUE));
 
        // Crée le mot de passe en se servant du salt généré ci-dessus 
        $password = hash('sha512', $password . $random_salt);
 
        // Enregistre le nouvel utilisateur dans la base de données
        if ($insert_stmt = $pdo->prepare("INSERT INTO admins (email, password, salt, actif_token) VALUES (?, ?, ?,?)"))
        {
            if ($insert_stmt->execute(array($email, $password, $random_salt,md5(uniqid(rand(), true)))))
            {
                $_SESSION['success'] = "Inscrit !";
                return true;
            } else {
                $_SESSION['error'] = "Erreur de base de données";
                return false;
            }
        } else {
            $_SESSION['error'] = "Erreur de base de données";
            return false;
        }
    } else {
        $_SESSION['error'] = "Erreur de base de données";
        return false; 
    }
}

/**
 * Return le resultat d'une requete SQL
 *
 * @param $query -> String
 * @param $fetch -> String fetch name
 * @param $type -> String fetch type
 * @param $sec_array -> Array of secure vars
 * @return array
 */
function getArrayFrom($query,$fetch = "fetchAll", $type = "FETCH_ASSOC", $sec_array = null)
{
    $pdo = SPDO::getInstance();
    if ($stmt = $pdo->prepare($query)) 
    {
        if(!is_array($sec_array)){
            $sec_array = array($sec_array);
        }
        if ($stmt->execute($sec_array))
        {
            switch ($fetch)
            {
                case 'fetchAll':
                    switch ($type) {
                        case 'FETCH_ASSOC':
                            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            break;
                        case 'FETCH_BOTH':
                            $row = $stmt->fetchAll(PDO::FETCH_BOTH);
                            break;
                        case 'FETCH_NUM':
                            $row = $stmt->fetchAll(PDO::FETCH_NUM);
                            break;
                        case 'FETCH_OBJ':
                            $row = $stmt->fetchAll(PDO::FETCH_OBJ);
                            break;
                    }
                    break;
                case 'fetch':
                    switch ($type) {
                        case 'FETCH_ASSOC':
                            $row = $stmt->fetch(PDO::FETCH_ASSOC);
                            break;
                        
                        case 'FETCH_BOTH':
                            $row = $stmt->fetch(PDO::FETCH_BOTH);
                            break;
                        
                        case 'FETCH_NUM':
                            $row = $stmt->fetch(PDO::FETCH_NUM);
                            break;
                        
                        case 'FETCH_OBJ':
                            $row = $stmt->fetch(PDO::FETCH_OBJ);
                            break;
                    }
                    break;
                case 'insert':
                    break;
                default:
                    break;
            }
            if (isset($row))
                return $row;
        }
    }
    return null;
}

function nbBonnesReponses($exercice_id, $reponses_user) {
    $reponses_bdd = getArrayFrom( "SELECT id_choix_bonn_rep, reponse_fixe FROM reponses WHERE id_question = ANY (SELECT id_question FROM questions WHERE id_exercice = ?)", "fetchAll", "FETCH_NUM", $exercice_id);
    $cpt = 0;
    foreach ($reponses_bdd as $key => $reponse_bdd)
    {
        // si reponse multiple
        if(is_array($reponses_user[$key]))
        {
            $rep_bdd_array = explode(',',$reponse_bdd[0]);
            $erreur = false;

            // Vérification erreurs
            foreach ($rep_bdd_array as $key2 => $rep_bdd) {
                if (!array_key_exists($key2,$reponses_user[$key]) or $rep_bdd != $reponses_user[$key][$key2])
                    $erreur = true;
            }
            if(!$erreur)
                $cpt++;
        }
        // si reponse simple
        else if($reponse_bdd[0] != null)
        {
            if($reponse_bdd[0] == $reponses_user[$key])
                $cpt++;
        }
        // si reponse fixe
        else if($reponse_bdd[1] != null)
        {
            $repbdd = str_replace(' ', '', strtolower($reponse_bdd[1]));
            $repuser = str_replace(' ', '', strtolower($reponses_user[$key]));

            if ($repbdd == $repuser)
                $cpt++;
        }
    }
    return $cpt;
}

function getSentenceResult($percent){
    if($percent == 0){
        return "Bon, c'est pas grave, essaye encore !";
    } else if ($percent <= 30){
        return "Essaye encore !";
    } else if ($percent == 50){
        return "La moitié de bon! ";
    } else if ($percent <= 60){
        return "Courage! Il faut travailler davantage";
    } else if ($percent <= 80){
        return "Encore un petit effort !";
    } else if ($percent < 100){
        return "Presque parfait!";
    } else if ($percent == 100){
        return "Parfait! Tu as tout bon!";
    } else {
        return "error";
    }
}

function saveScore($exercice_id, $nbBonnesReponses){
    if(isset($_SESSION['Auth']['user']) and isset($_SESSION['Auth']['isStudent']))
    {
        if ($nbBonnesReponses >= 0)
        {
            $score = getArrayFrom("SELECT * FROM scores WHERE id_etudiant = ? and id_exercice = ?", "fetch", "FETCH_ASSOC", array($_SESSION['Auth']['user'],$exercice_id));

            if(!$score)
                getArrayFrom("INSERT INTO scores (resultat_ancien, id_etudiant, id_exercice, resultat) VALUES (resultat,?,?,?)", "insert", null, array($_SESSION['Auth']['user'], $exercice_id, $nbBonnesReponses));
            else
                getArrayFrom("UPDATE scores SET resultat_ancien = resultat, resultat = ?", "insert", null, array($nbBonnesReponses));
            $_SESSION['success'] = "Score sauvegardé";
        } else
            $_SESSION['error'] = "Erreur lors de la sauvegarde du score";
    } else {
        $_SESSION['error'] = "Vous n'êtes pas étudiant, il n'y a donc pas de sauvegarde du score";
    }
}