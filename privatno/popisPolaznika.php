<?php
include_once '../konfiguracija.php'; 
provjeraOvlasti();
	
	$izraz = $veza->prepare("
			select concat(c.prezime, ' ', c.ime) as polaznik from 
			 clan a inner join polaznik b on a.polaznik=b.sifra
			inner join osoba c on b.osoba=c.sifra
			 where a.grupa = :grupa order by 1;

	");
	$izraz->execute($_POST);
	$rezultati = $izraz->fetchAll(PDO::FETCH_OBJ);
	echo json_encode($rezultati);