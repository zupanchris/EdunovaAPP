<?php
include_once '../../konfiguracija.php'; 
provjeraOvlasti();
	
	$izraz = $veza->prepare("
			select * from  grupa a inner join clan b on a.sifra=b.grupa
			 where b.polaznik = :polaznik;

	");
	$izraz->execute($_POST);
	$rezultati = $izraz->fetchAll(PDO::FETCH_OBJ);
	echo json_encode($rezultati);