<?php
include_once '../../konfiguracija.php'; 
provjeraOvlasti();
	
	$izraz = $veza->prepare("
			select * from  grupa where smjer = :smjer;

	");
	$izraz->execute($_POST);
	$rezultati = $izraz->fetchAll(PDO::FETCH_OBJ);
	echo json_encode($rezultati);