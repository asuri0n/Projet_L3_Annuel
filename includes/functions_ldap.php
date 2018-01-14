<?php

function creerConnexionLdap(){
	$baseDN = "dc=iutc3,dc=unicaen,dc=fr";
	$ldapServer = "ruche";
	$ldapServerPort = 389;
	$mdp="WestBam42";
	$dn = 'uid=armand.baglin,ou=People,dc=iutc3,dc=unicaen,dc=fr';

	//echo "Connexion au serveur <br />";
	$conn=ldap_connect($ldapServer,$ldapServerPort);

	// on teste : le serveur LDAP est-il trouvé ?
	if ($conn)
	 echo "Le résultat de connexion est: ".$conn ."<br />";
	else
	 die("connexion impossible au serveur LDAP");

	/* 2ème étape : on effectue une liaison au serveur, ici de type "anonyme"
	 * pour une recherche permise par un accès en lecture seule */

	// On dit qu'on utilise LDAP V3, sinon la V2 par défaut est utilisé
	// et le bind ne passe pas. 
	if (ldap_set_option($conn, LDAP_OPT_PROTOCOL_VERSION, 3))
	{
	//  echo "Utilisation de LDAP V3 : OK \n";
	} 
	else 
	{
	//  echo "Impossible d'utiliser LDAP V3\n";
	  exit;  
	}

	$bindServerLDAP=ldap_bind($conn,$dn,$mdp);

	print ("Liaison au serveur : ". ldap_error($conn)."\n");
	echo "ldap error : ".ldap_error($conn)."</br>";
	// en cas de succès de la liaison, renvoie Vrai
	if ($bindServerLDAP)
	  echo "Le résultat de connexion est ".$bindServerLDAP." <br />";
	else
	  die("Liaison impossible au serveur ldap ...");

	return $conn;
}


function rechercheLdap($conn,$baseDN,$query){
	echo 'conn :'.$conn."</br>";
	echo 'baseDN :'.$baseDN."</br>";
	echo 'query :'.$query."</br>";
	
$result=ldap_search($conn, $baseDN, $query);

return $result;
}

function nbEntrees($conn,$result){
	$nbentrees=ldap_count_entries($conn,$result);
	return $nbentrees;
}

?>
