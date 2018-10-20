<?php
require "config.php";

$code = $_GET["code"];
if( isset($code) ){
	$data = $instagram->getOAuthToken($code);
	header("location:index.php?token=" . $data->access_token);
}