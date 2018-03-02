<?php
/*
 *  Page system
 */
if(isset($_GET['p']))
	$params = explode("/", $_GET['p']);

if(!isset($params[0]))
	$params[0]='accueil';

if(!file_exists('pages/'.$params[0].'.php'))
	$params[0]='404';

ob_start();
	include 'pages/'.$params[0].'.php';
	$content = ob_get_contents();
ob_end_clean();