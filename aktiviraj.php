<?php

if(!isset($_GET["id"])){
	exit;
}

include_once 'konfiguracija.php';
$izraz=$veza->prepare("update operater set aktivan = 1, sessionid=null where sessionid=:id;");
$izraz->execute($_GET);

header("location: login.php");


?>

