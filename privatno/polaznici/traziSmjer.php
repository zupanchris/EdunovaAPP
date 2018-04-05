<?php
include_once '../../konfiguracija.php'; 
provjeraOvlasti();
	
	$izraz = $veza->prepare("
			select a.* from smjer a inner join grupa b
			on a.sifra=b.smjer
			 where a.sifra in (
			select sifra from grupa where sifra not in (
			select 
			distinct b.sifra
			from clan a inner join grupa b
			on a.grupa=b.sifra
			where a.polaznik=:polaznik));

	");
	$izraz->execute($_POST);
	$rezultati = $izraz->fetchAll(PDO::FETCH_OBJ);
	
	
	
	echo json_encode($rezultati);