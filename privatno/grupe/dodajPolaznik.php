<?php

include_once '../../konfiguracija.php'; 
provjeraOvlasti();
	
	$izraz = $veza->prepare("
			insert into clan(grupa,polaznik) values (:grupa,:polaznik);
	");
	$izraz->execute($_POST);
	echo "OK";
