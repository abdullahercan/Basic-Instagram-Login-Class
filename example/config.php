<?php
error_reporting(E_ALL ^ E_NOTICE);

require "../instagram.php";

$instagram = new instagram([
	"key" => "API_KEY",
	"secret" => "API_SECRET",
	"callback" => "CALLBACK_URL"
]);

$token = isset($_GET["token"]) ? $_GET["token"] : false;
if( $token ){
	$instagram->setAccessToken($token);
}