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
    if ($stmt = $pdo->prepare("SELECT id_admin, email, password, salt FROM admins WHERE email = ? LIMIT 1"))
    {
        if ($stmt->execute(array($email)))
        {
            $row = $stmt->fetch(PDO::FETCH_BOTH);
            if ($row)
            {
                $id_admin = $row[0];
                $email = $row[1];
                $db_password = $row[2];
                $salt = $row[3];

				// Hashe le mot de passe avec le salt unique
				$password = hash('sha512', $password . $salt);
                // Vérifie si les deux mots de passe sont les mêmes
                // Le mot de passe que l’utilisateur a donné.
                if ($db_password == $password) {
                    // Protection XSS car nous pourrions conserver cette valeur
                    $id_admin = preg_replace("/[^0-9]+/", "", $id_admin);
                    $_SESSION['Auth']['id'] = $id_admin;
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

        if (!newSQLQuery("SELECT * FROM etudiants WHERE id_etudiant = ".$etupass, "select", "fetch")) {
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
    if (isset($_POST['email'], $_POST['password'])) {
        // Nettoyez et validez les données transmises au script
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $email = filter_var($email, FILTER_VALIDATE_EMAIL);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = "Adresse mail non valide";
            return false;
        }
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

        // La forme du nom d’utilisateur et du mot de passe a été vérifiée côté client
        // Cela devrait suffire, car personne ne tire avantage
        // à briser ce genre de règles.
        //
     
        $prep_stmt = "SELECT id_admin FROM admins WHERE email = ? LIMIT 1";
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
        if ($insert_stmt = $pdo->prepare("INSERT INTO admins (email, password, salt) VALUES (?, ?, ?)"))
        {
            if ($insert_stmt->execute(array($email, $password, $random_salt)))
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
 * @param $query
 * @param $typeQuery
 * @param string $fetch
 * @param string $typeFetch
 * @param null $sec_array
 * @return bool|null|array
 */
function newSQLQuery($query, $typeQuery, $fetch = "fetchAll", $typeFetch = "FETCH_ASSOC", $sec_array = null)
{
    $pdo = SPDO::getInstance();
    switch($typeQuery)
    {
        case 'select':
            if(!is_array($sec_array) and $sec_array != null)
                $sec_array = array($sec_array);

            if($stmt = $pdo->prepare($query)) {
                if ($stmt->execute($sec_array)) {
                    switch ($fetch) {
                        case 'fetchAll':
                            switch ($typeFetch) {
                                case 'FETCH_ASSOC':
                                    return $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    break;
                                case 'FETCH_BOTH':
                                    return $stmt->fetchAll(PDO::FETCH_BOTH);
                                    break;
                                case 'FETCH_NUM':
                                    return $stmt->fetchAll(PDO::FETCH_NUM);
                                    break;
                                case 'FETCH_OBJ':
                                    return $stmt->fetchAll(PDO::FETCH_OBJ);
                                    break;
                            }
                            break;
                        case 'fetch':
                            switch ($typeFetch) {
                                case 'FETCH_ASSOC':
                                    return $stmt->fetch(PDO::FETCH_ASSOC);
                                    break;

                                case 'FETCH_BOTH':
                                    return $stmt->fetch(PDO::FETCH_BOTH);
                                    break;

                                case 'FETCH_NUM':
                                    return $stmt->fetch(PDO::FETCH_NUM);
                                    break;

                                case 'FETCH_OBJ':
                                    return $stmt->fetch(PDO::FETCH_OBJ);
                                    break;
                            }
                            break;
                        default:
                            break;
                    }
                }
            }
            break;
        case 'delete':
            if($stmt = $pdo->prepare($query))
                if ($stmt->execute($sec_array))
                    return $stmt->rowCount();
            break;
        case 'update':
            if($stmt = $pdo->prepare($query))
                if ($stmt->execute($sec_array))
                    return $stmt->rowCount();
            break;
        case 'insert':
            if($stmt = $pdo->prepare($query))
                if ($stmt->execute($sec_array))
                    return true;
            break;

    }
    return null; // Si erreur
}

function nbBonnesReponses($exercice_id, $reponses_user) {
    $reponses_bdd = newSQLQuery( "SELECT id_choix_bonn_rep, reponse_fixe FROM reponses WHERE id_question = ANY (SELECT id_question FROM questions WHERE id_exercice = ?)", "select","fetchAll", "FETCH_NUM", $exercice_id);
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
        else if(is_integer($reponses_user[$key]) and $reponse_bdd[0] != null)
        {
            if($reponse_bdd[0] == $reponses_user[$key])
                $cpt++;
        }
        // si reponse fixe
        else if((is_string($reponses_user[$key]) or is_integer($reponses_user[$key])) and $reponse_bdd[1] != null)
        {
            $repbdd = str_replace(' ', '', strtolower(stripslashes($reponse_bdd[1])));
            $repuser = str_replace(' ', '', strtolower(stripslashes($reponses_user[$key])));

            if ($repbdd == $repuser) {
                $cpt++;
            }
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
            $score = newSQLQuery("SELECT resultat_ancien FROM scores WHERE id_etudiant = ? and id_exercice = ?", "select","fetch", "FETCH_ASSOC", array($_SESSION['Auth']['user'],$exercice_id));

            if(!$score) {
                newSQLQuery("INSERT INTO scores (resultat_ancien, id_etudiant, id_exercice, resultat) VALUES (resultat,?,?,?)", "insert", null, null, array($_SESSION['Auth']['user'], $exercice_id, $nbBonnesReponses));
                $_SESSION['success'] = "Score sauvegardé";
                return null;
            } else {
                if ($score['resultat_ancien'] != $nbBonnesReponses ){
                    newSQLQuery("UPDATE scores SET resultat_ancien = resultat, resultat = ?", "insert", null, null, array($nbBonnesReponses));
                    $_SESSION['success'] = "Score mis a jour";
                }
                return $score['resultat_ancien'];
            }
        } else
            $_SESSION['error'] = "Erreur lors de la sauvegarde du score";
    } else {
        $_SESSION['error'] = "Vous n'êtes pas étudiant, il n'y a donc pas de sauvegarde du score";
    }
    return null;
}

function timeElapsed($datetime) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'année',
        'm' => 'mois',
        'w' => 'semaines',
        'd' => 'jour',
        'h' => 'heure',
        'i' => 'minute',
        's' => 'seconde',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            if($k != 'm')
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    $string = array_slice($string, 0, 1);

    return $string ? 'il y a ' . implode(', ', $string) : 'just now';
}