<?php
	error_reporting(-1);
  	ini_set('display_errors','On');
	mb_internal_encoding('UTF-8');
	date_default_timezone_set("Europe/Paris");

	define("WEB_TITLE", "Auto Evaluation");

	/*
	 * Global Variables
	 */
	//define("CAN_REGISTER", "any");
	//define("DEFAULT_ROLE", "member");
    define('WEBROOT', str_replace('index.php', '', $_SERVER['SCRIPT_NAME']));
    define('PROTOCOL',$_SERVER['HTTP_HOST']);
    define('PATH', "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
	define("UFRSCIENCES","13;UFR des Sciences");

    /*
     * DATABASE
     */
    if(PROTOCOL == "localhost")
    {
        define("HOST", "localhost");
        define("USER", "root");
        define("PASSWORD", "");
        define("DATABASE", "autoevaluation_projetl3");
    }
    else if(PROTOCOL == "dev-21404260.users.info.unicaen.fr")
    {
        define("HOST", "mysql.info.unicaen.fr");
        define("USER", "21404260");
        define("PASSWORD", "DaeSheeWu6weephi");
        define("DATABASE", "21404260_dev");
    } else {
        define("HOST", "localhost");
        define("USER", "root");
        define("PASSWORD", "");
        define("DATABASE", "autoevaluation_projetl3");
    }

    require_once ('SPDO.php');
    $pdo = SPDO::getInstance();

    /*
     * SESSION
     */
    define("SECURE", FALSE);
    sec_session_start();

    /*
     * LOGIN SYSTEM
     */
    //require 'Auth.php';