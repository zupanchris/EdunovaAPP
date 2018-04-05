<?php

include_once '../../konfiguracija.php'; 
provjeraOvlasti();
	
	$izraz = $veza->prepare("
			delete from clan where grupa=:grupa and polaznik=:polaznik;
	");
	$izraz->execute($_POST);
	echo "OK";
