<?php
	error_reporting(-1);
  	ini_set('display_errors','On');
	mb_internal_encoding('UTF-8');
	date_default_timezone_set("Europe/Paris");

	define("WEB_TITLE", "Auto Evaluation");

	/*
	 * DATABASE
	 */
	define("HOST", "localhost");
	define("USER", "root"); 
	define("PASSWORD", "");
	define("DATABASE", "autoevaluation_projetl3");
	 
	define("CAN_REGISTER", "any");
	define("DEFAULT_ROLE", "member");
	 
	define("SECURE", FALSE);

	$pages = [
        "Accueil" => ["/accueil",0],
        "Exercices" => ["/exercices",0],
        "Admin" => ["/admin",1],
    ];

	/*
	 * DATABASE
	 */
	require_once ('SPDO.php');
	$pdo = SPDO::getInstance();