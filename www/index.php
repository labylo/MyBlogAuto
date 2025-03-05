<?php 
require_once( __DIR__ . "/../init.php");
session_start();
if ( DEV ) {
	error_reporting(E_ALL);
	ini_set("display_errors", 1);
}
$objectInstancier->FrontControler->go();
