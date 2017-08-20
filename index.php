<?php
	include_once('utils/constants.php');
	include_once('utils/functions.php');
	include_once('utils/autoloader.php');
	include_once('model/db.php');

	setlocale(LC_ALL, "sv_SE", "sv_SE.UTF-8", "swedish", "swedish.UTF-8");
	
	session_start();

	// the use of this module is to catch the incoming requests and push them in the right direction.
	$control = "home";
	$action  = "show";
	
	if(array_key_exists("controller", $_GET)) 
	{
		$control = $_GET["controller"];
	}

	if(array_key_exists("action", $_GET)) 
	{
		$action = $_GET["action"];
	}

	if(!isAuthenticated()) {
		if(($control == 'user' && $action == 'register') || ($control == 'user' && $action == 'login') || ($control == 'home' && $action == 'show_register') || ($control == 'display' && $action == 'show') || ($control == 'display' && $action == 'needsRefresh')) {
		} else {
			$control = 'home';
			$action = 'show_unauth';
		}
	}
	
	loadController($control, $action);
?>