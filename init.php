<?php
$debut = microtime(true);

date_default_timezone_set('Europe/Paris');

setlocale(LC_ALL,"fr_FR.UTF-8");
ini_set("default_charset","utf-8");

set_include_path(
	__DIR__ . "/model/" . PATH_SEPARATOR . 
	__DIR__ . "/lib/" . PATH_SEPARATOR . 
	__DIR__ . "/controler/". PATH_SEPARATOR .
	get_include_path()
);

require_once("lib/hecho.function.php");
require_once("lib/session.function.php");

spl_autoload_register(function($class_name) {
	$class_filename = "{$class_name}.class.php";
	
	if (! stream_resolve_include_path($class_filename)){
		throw new Exception("Unable to find $class_filename in include_path : ".get_include_path());
	}
	
	require_once($class_name . ".class.php");
});

$objectInstancier = new ObjectInstancier();

$lastMessage = new LastMessage();
$objectInstancier->LastMessage = $lastMessage;
$objectInstancier->debut = $debut;

require_once( __DIR__."/LocalSettings.php");
require_once( __DIR__."/SiteSettings.php");

$objectInstancier->site_index = SITE_URL;


$objectInstancier->databaseName = $database_name;
$objectInstancier->SQLQuery->setDatabaseHost($database_host);
$objectInstancier->SQLQuery->setCredential($database_login,$database_password);
$objectInstancier->template_path = array(__DIR__."/template/");

if ( ! PRODUCTION ) {
	$objectInstancier->LesscAdapter->createCSS(__DIR__.'/less.css', __DIR__.'/www/css/style.css');
}



